<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Quote;
use App\Models\Role;
use App\Models\Smmes;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectRepository implements ProjectRepositoryInterface
{
	public function getPaginatedProjects(int $perPage): LengthAwarePaginator
	{
		return Project::with('smme')->paginate($perPage);
	}

	public function getCreateData(): array
	{
		return [
				'assets' => Asset::where('status', Asset::AVAILABLE)->get(),
				'projectTypes' => ProjectType::all(),
				'quotes' => Quote::all(),
				'teamLeaders' => User::all(),
				'locations' => Location::all(),
				'smmes' => Smmes::all(),
		];
	}

	public function findOrFail(int $id): Project
	{
		return Project::findOrFail($id);
	}

	public function getEditData($project): array
	{
		$project = $this->findOrFail($project);
		return array_merge($this->getCreateData(), [
				'project' => $project,
				'assets' => Asset::where('project_id', $project->id)
						->orWhere('status', Asset::AVAILABLE)
						->get(),
				'selectedAssets' => $project->assets->pluck('id')->toArray(),
		]);
	}

	public function store(array $data): Project
	{
		return DB::transaction(function () use ($data) {
			$project = Project::create($data);
			$this->syncAssets($project, $data['assets'] ?? []);
			return $project;
		});
	}

	public function update(Project $project, array $data): Project
	{
		return DB::transaction(function () use ($project, $data) {
			$project->update($data);
			$this->syncAssets($project, $data['assets'] ?? []);
			return $project;
		});
	}

	public function delete(Project $project): bool
	{
		$this->detachAssets($project->assets);
		return $project->delete();
	}

	public function syncAssets(Project $project, array $assetIds): void
	{
		$assetsToDetach = $project->assets()->whereNotIn('id', $assetIds)->get();
		$this->detachAssets($assetsToDetach);
		$this->attachAssets($assetIds, $project);
	}

	private function detachAssets($assets): void
	{
		$assets->each(function ($asset) {
			$asset->update([
					'project_id' => null,
					'status' => Asset::AVAILABLE
			]);
		});
	}

	private function attachAssets(array $assetIds, Project $project): void
	{
		Asset::whereIn('id', $assetIds)->update([
				'project_id' => $project->id,
				'status' => Asset::IN_USE
		]);
	}

	public function getTeamLeader(Project $project): ?User
	{
		return $project->team_leader_user_id ? User::find($project->team_leader_user_id) : null;
	}

	public function getProjectSmme(Project $project)
	{
		return $project->relationLoaded('smme') ? $project->smme : $project->smme()->first();
	}

	public function calculateProjectProgress($project): array
	{
		if (!$project instanceof Project) {
			$project = $this->findOrFail($project);
		}

		$startDate = new DateTime($project->startDate);
		$endDate = new DateTime($project->endDate);
		$currentDate = new DateTime();

		$totalDays = $endDate->diff($startDate)->days + 1;
		$currentDay = $currentDate->diff($startDate)->days + 1;
		$targetPerDay = $project->target_hectares / $totalDays;
		$expectedProgress = $currentDay * $targetPerDay;

		$actualProgressPercentage = null;
		$expectedProgressPercentage = null;
		$differenceFromTarget = 0;
	
		// Only calculate percentages for projectType 1
		if ($project->projectType->id == 1) {
			$actualProgressPercentage = ($project->actual_hectares / $project->target_hectares) * 100;
			$expectedProgressPercentage = ($expectedProgress / $project->target_hectares) * 100;
			$differenceFromTarget = $actualProgressPercentage - $expectedProgressPercentage;
		}

		return [
				'totalDays' => $totalDays,
				'currentDay' => $currentDay,
				'targetPerDay' => $targetPerDay,
				'expectedProgress' => $expectedProgress,
				'actualProgressPercentage' => $actualProgressPercentage,
				'expectedProgressPercentage' => $expectedProgressPercentage,
				'differenceFromTarget' => $differenceFromTarget,
				'status' => $this->getProjectStatus($differenceFromTarget),
		];
	}

	private function getProjectStatus(float $differenceFromTarget): string
	{
		if ($differenceFromTarget < 0) {
			return 'Behind schedule';
		} elseif ($differenceFromTarget == 0) {
			return 'ON schedule';
		} else {
			return 'Ahead of schedule';
		}
	}

	public function countProjects(array $filters = []): int
	{
		return $this->applyFilters($filters)->count();
	}

	public function countStudents(array $filters = []): int
	{
		$query = $this->applyFilters($filters);
		$result = $query->sum('number_of_students');
		Log::info($query->toSql() . " " . $result);
		return $result;
	}

	public function countTargetVehicleKms(array $filters = []): int
	{
		return $this->applyFilters($filters)->sum('vehicle_kms_target');
	}

	public function countActualVehicleKms(array $filters = []): int
	{
		return $this->applyFilters($filters)->sum('actual_vehicle_kms');
	}


	public function getRecentMonthsActualBudget(int $numberOfMonths = 3): array
	{
		$result = $this->query()
				->select(
						DB::raw('MONTH(created_at) as month'),
						DB::raw('SUM(actual_budget) as total')
				)
				->where('created_at', '>=', Carbon::now()->subMonths($numberOfMonths))
				->groupBy('month')
				->orderBy('month', 'DESC')
				->get()
				->pluck('total', 'month')
				->toArray();

		$budgetData = [];
		for ($i = $numberOfMonths - 1; $i >= 0; $i--) {
			$date = Carbon::now()->subMonths($i);
			$monthName = $date->format('M');
			$monthNumber = $date->month;
			$budgetData[$monthName] = $result[$monthNumber] ?? 0;
		}

		return $budgetData;
	}



	public function query(): Builder
	{
		return Project::query();
	}

	private function applyFilters(array $filters = []): Builder
	{
		$query = $this->query();

		if (isset($filters['project_type_id_slug'])) {
			$slug = $filters['project_type_id_slug'];
			$query->whereHas('projectType', function ($q) use ($slug) {
				$q->where('slug', $slug);
			});
			unset($filters['project_type_id_slug']);
		}

		foreach ($filters as $field => $value) {
			if (is_array($value)) {
				$operator = $value[0] ?? '=';
				$filterValue = $value[1] ?? null;
				$query->where($field, $operator, $filterValue);
			} else {
				$query->where($field, $value);
			}
		}

		return $query;
	}
}