<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
	public function getPaginatedProjects(int $perPage): LengthAwarePaginator;
	public function getCreateData(): array;
	public function findOrFail(int $id): Project;
	public function getEditData($project): array;
	public function store(array $data): Project;
	public function update(Project $project, array $data): Project;
	public function delete(Project $project): bool;
	public function syncAssets(Project $project, array $assetIds): void;
	public function calculateProjectProgress($project);
	public function getTeamLeader(Project $project);
	public function getProjectSmme(Project $project);
	public function countProjects();
	public function countStudents();
	public function countTargetVehicleKms();
	public function countActualVehicleKms();
	public function getRecentMonthsActualBudget(int $numberOfMonths = 3): array;
}