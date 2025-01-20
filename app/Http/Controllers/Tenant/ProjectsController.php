<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\InvoiceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
	protected ProjectRepositoryInterface $projectRepository;
	protected InvoiceRepositoryInterface $invoiceRepository;

	public function __construct(
			ProjectRepositoryInterface $projectRepository,
			InvoiceRepositoryInterface $invoiceRepository
	) {
		$this->middleware('auth');
		$this->projectRepository = $projectRepository;
		$this->invoiceRepository = $invoiceRepository;
	}

	/**
	 * @return View
	 */
	public function index(Request $request): View
	{
		$query = $this->projectRepository->query();

		// Apply search filter
		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function($q) use ($search) {
				$q->where('project_name', 'like', "%{$search}%")
						->orWhereHas('projectType', function($q) use ($search) {
							$q->where('name', 'like', "%{$search}%");
						})
						->orWhereHas('teamLeader', function($q) use ($search) {
							$q->where('name', 'like', "%{$search}%");
						});
			});
		}

		if ($request->filled('type')) {
			$query->where('project_type_id', $request->type);
		}

		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		if ($request->filled('team_leader')) {
			$query->where('team_leader_user_id', $request->team_leader);
		}

		$allowedProjectTypeIds = [];

		if (Gate::allows('has_permission', 'can_access_veg')) {
			$allowedProjectTypeIds[] = ProjectType::where('name', 'Vegetation Management')->value('id');
		}
		if (Gate::allows('has_permission', 'can_access_training')) {
			$allowedProjectTypeIds[] = ProjectType::where('name', 'Training')->value('id');
		}
		if (Gate::allows('has_permission', 'can_access_innovation')) {
			$allowedProjectTypeIds[] = ProjectType::where('name', 'Innovation')->value('id');
		}
		if (Gate::allows('has_permission', 'can_access_planning')) {
			$allowedProjectTypeIds[] = ProjectType::where('name', 'Planning')->value('id');
		}

		// If no project types are allowed, return empty collection
		if (empty($allowedProjectTypeIds)) {
			return view('tenant.projects.index', ['projects' => collect()]);
		}

		$query->whereIn('project_type_id', $allowedProjectTypeIds);

		$projects = $query->paginate(10);

		// Get statistics
		$totalProjects = $this->projectRepository->countProjects();
		$projectVegCount = $this->projectRepository->countProjects(['project_type_id_slug' => 'vegetation-management']);
		$totalRevenueString = $this->invoiceRepository->getOverallTotalString();
		$vehicleTargetKms = $this->projectRepository->countTargetVehicleKms();
		$vehicleActualKms = $this->projectRepository->countActualVehicleKms();

		$projectTypes = ProjectType::all();
		$teamLeaders = User::All();

		return view('tenant.projects.index', compact(
				'projects',
				'totalProjects',
				'projectVegCount',
				'totalRevenueString',
				'vehicleTargetKms',
				'vehicleActualKms',
				'projectTypes',
				'teamLeaders'
		));
	}

	public function create(): View
	{
		if (!Gate::allows('has_permission','can_create_projects')) {
			return view('errors.403');
		}
		$data = $this->projectRepository->getCreateData();
		return view('tenant.projects.create', $data);
	}

	public function show($id)
	{
		if (!Gate::allows('has_permission','can_view_projects')) {
			return view('errors.403');
		}

		$project = $this->projectRepository->findOrFail($id);
		$teamLeader = $this->projectRepository->getTeamLeader($project);
		$smme = $this->projectRepository->getProjectSmme($project);

		$project->formatted_start_date = $project->startDate ? Carbon::parse($project->startDate)->format('M d, Y') : 'Not set';
		$project->formatted_end_date = $project->endDate ? Carbon::parse($project->endDate)->format('M d, Y') : 'Not set';

		$teamLeaderName = $teamLeader->name;
		$progress = null;
		if ($project->projectType->id == 1) {
			$progress = $this->projectRepository->calculateProjectProgress($project);
			return view('tenant.projects.veg-show', compact('project', 'progress', 'teamLeaderName', 'smme'));
		}

		if ($project->projectType && $project->projectType->id == 2) {
			$progress = $this->projectRepository->calculateProjectProgress($project);
			return view('tenant.projects.train-show', compact('project', 'progress', 'teamLeaderName', 'smme'));
		}
		
		if ($project->projectType && $project->projectType->id == 3) {
			$progress = $this->projectRepository->calculateProjectProgress($project);
			return view('tenant.projects.inno-show', compact('project', 'progress', 'teamLeaderName', 'smme'));
		}

		if ($project->projectType && $project->projectType->id == 4) {
			$progress = $this->projectRepository->calculateProjectProgress($project);
			return view('tenant.projects.plan-show', compact('project', 'progress', 'teamLeaderName', 'smme'));
		}


		return view('tenant.projects.show', compact('project', 'progress', 'teamLeaderName'));
	}

	public function edit($project)
	{
		if (!Gate::allows('has_permission','can_edit_projects')) {
			return view('errors.403');
		}
		$data = $this->projectRepository->getEditData($project);
		return view('tenant.projects.edit', $data);
	}

	public function store(ProjectRequest $request)
	{
		$project = $this->projectRepository->store($request->validated());
		return redirect()->route('tenant.projects.index')->with('success', 'Project created successfully!');
	}


	public function update(ProjectRequest $request, Project $project)
	{
		$this->projectRepository->update($project, $request->validated());
		return redirect()->route('tenant.projects.show', $project)->with('success', 'Project updated successfully.');
	}

	public function destroy(Project $project)
	{
		$this->projectRepository->delete($project);
		return redirect()->route('tenant.projects.index')->with('success', 'Project deleted successfully.');
	}

}