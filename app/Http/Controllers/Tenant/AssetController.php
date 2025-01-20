<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Http\Request;

class AssetController extends Controller
{
	public function index(Request $request)
	{
		$query = Asset::query();

		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function ($q) use ($search) {
				$q->where('name', 'like', "%{$search}%");
			});
		}
		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}
		if ($request->filled('location')) {
			$query->where('location', $request->location);
		}
		if ($request->filled('model')) {
			$query->where('model', $request->model);
		}
		if ($request->filled('cost')) {
			$range = $request->cost;
			if ($range === '150000+') {
				$query->where('cost', '>', 150000);
			} else {
				list($min, $max) = explode('-', $range);
				$query->whereBetween('cost', [$min, $max]);
			}
		}
		if ($request->filled('asset_type_id')) {
			$query->where('asset_type_id', $request->asset_type_id);
		}
		$sortColumn = $request->get('sort', 'name');
		$sortDirection = $request->get('direction', 'asc');
		$allowedColumns = ['name', 'serial_number', 'model', 'status', 'cost', 'location'];
		if (in_array($sortColumn, $allowedColumns)) {
			$query->orderBy($sortColumn, $sortDirection);
		}

		$assets = $query->latest()->paginate(10);
		return view('tenant.assets.index', compact('assets'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate(['name' => 'required|string|max:255', 'serial_number' => 'required|string|unique:assets', 'model' => 'required|string|max:255', 'status' => 'required|in:In use,Not in use,In service', 'cost' => 'required|numeric', 'location' => 'required|string|max:255', 'purchase_date' => 'required|date', 'warranty_date' => 'required|date', 'asset_type_id' => 'required'

		]);

		Asset::create($validated);

		return redirect()->route('tenant.assets.index')->with('success', 'Asset created successfully.');
	}

	public function create()
	{
		$assetTypes = AssetType::all();
		return view('tenant.assets.create', compact('assetTypes'));
	}

	public function show(Asset $asset)
	{
		return view('tenant.assets.show', compact('asset'));
	}

	public function edit(Asset $asset)
	{
		$assetTypes = AssetType::all();
		return view('tenant.assets.edit', compact('asset', 'assetTypes'));
	}

	public function update(Request $request, Asset $asset)
	{
		$validated = $request->validate(['name' => 'required|string|max:255', 'serial_number' => 'required|string|unique:assets,serial_number,' . $asset->id, 'model' => 'required|string|max:255', 'status' => 'required|in:In use,Not in use,In service', 'cost' => 'required|numeric', 'location' => 'required|string|max:255', 'purchase_date' => 'required|date', 'warranty_date' => 'required|date', 'asset_type_id' => 'required']);

		$asset->update($validated);

		return redirect()->route('tenant.assets.index')->with('success', 'Asset updated successfully.');
	}

	public function destroy(Asset $asset)
	{
		$asset->delete();

		return redirect()->route('tenant.assets.index')->with('success', 'Asset deleted successfully.');
	}
}