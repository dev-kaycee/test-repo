<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Project;
use App\Repositories\AssetRepositoryInterface;
use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\QuoteRepositoryInterface;
use App\Repositories\SmmeRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	protected ProjectRepositoryInterface $projectRepository;
	protected InvoiceRepositoryInterface $invoiceRepository;
	protected QuoteRepositoryInterface $quoteRepository;
	protected AssetRepositoryInterface $assetRepository;

	protected SmmeRepositoryInterface $smmeRepository;

	public function __construct(
			ProjectRepositoryInterface $projectRepository,
			InvoiceRepositoryInterface $invoiceRepository,
			QuoteRepositoryInterface $quoteRepository,
			AssetRepositoryInterface $assetRepository,
			SmmeRepositoryInterface $smmeRepository

	) {
		$this->middleware('auth');
		$this->projectRepository = $projectRepository;
		$this->invoiceRepository = $invoiceRepository;
		$this->quoteRepository = $quoteRepository;
		$this->assetRepository = $assetRepository;
		$this->smmeRepository = $smmeRepository;
	}

	public function index(): View
	{
		$user = Auth::user();

//		$user->assignPermission('can_access_finance');
		$projectTypes = ['innovation', 'training', 'vegetation-management', 'planning'];
		$statuses = [Project::STATUS_PLANNED, Project::STATUS_IN_PROGRESS, Project::STATUS_COMPLETED];

		$projectCounts = $this->getProjectCounts($projectTypes, $statuses);

		$threeMonthsInvoicedTotalData = $this->invoiceRepository->getLastThreeMonthsInvoicedTotal();
		$quotedData = $this->quoteRepository->getLastThreeMonthsQuotedTotal();

		$months = [];
		$threeMonthsInvoicedTotal = [];

		//Assets
		$inUseAssetsCount = $this->assetRepository->countAssetsByStatus(Asset::IN_USE);
		$availableAssetsCount = $this->assetRepository->countAssetsByStatus(Asset::AVAILABLE);
		$inServiceAssetsCount = $this->assetRepository->countAssetsByStatus(Asset::IN_SERVICE);

		for ($i = 2; $i >= 0; $i--) {
			$month = Carbon::now()->subMonths($i);
			$months[] = $month->format('M');
			$threeMonthsInvoicedTotal[] = $threeMonthsInvoicedTotalData[$month->month] ?? 0;
			$threeMonthsQuotedTotal[] = $quotedData[$month->month] ?? 0;
		}

		$data = [
				'totalProjects' => $this->projectRepository->countProjects(),
				'projectVegCount' => $projectCounts['vegetation-management']['total'],
				'totalRevenueString' => $this->invoiceRepository->getOverallTotalString(),
				'threeMonthsActualBudgetTotal' => $this->projectRepository->getRecentMonthsActualBudget(),
				'totalStudents' => $this->projectRepository->countStudents(),
				'vehicleTargetKms' => $this->projectRepository->countTargetVehicleKms(),
				'vehicleActualKms' => $this->projectRepository->countActualVehicleKms(),
				'projectCounts' => $projectCounts,
				'PROJECT_STATUS_PLANNED' => Project::STATUS_PLANNED,
				'PROJECT_STATUS_IN_PROGRESS' => Project::STATUS_IN_PROGRESS,
				'PROJECT_STATUS_COMPLETED' => Project::STATUS_COMPLETED,
				'months' => $months,
				'threeMonthsInvoicedTotal' => $threeMonthsInvoicedTotal,
				'threeMonthsQuotedTotal' => $threeMonthsQuotedTotal,
			  'inUseAssetsCount' => $inUseAssetsCount,
				'availableAssetsCount' => $availableAssetsCount,
				'inServiceAssetsCount' => $inServiceAssetsCount,
				'smmeStatusCounts' =>  $this->smmeRepository->getSmmeCountByStatus(),
		];

		return view('home', $data);
	}

	private function getProjectCounts(array $projectTypes, array $statuses): array
	{
		$counts = [];

		foreach ($projectTypes as $type) {
			$counts[$type] = ['total' => $this->projectRepository->countProjects(['project_type_id_slug' => $type])];
			foreach ($statuses as $status) {
				$counts[$type][$status] = $this->projectRepository->countProjects([
						'project_type_id_slug' => $type,
						'status' => $status
				]);
			}
		}

		return $counts;
	}
}