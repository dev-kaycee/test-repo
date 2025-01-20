<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Smmes;
use Illuminate\Http\Request;

class SmmeController extends Controller
{

	public function index(Request $request)
	{
		$query = Smmes::query();

		// Apply search filter
		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function ($q) use ($search) {
				$q->where('name', 'like', "%{$search}%");
//						->orWhere('registration_number', 'like', "%{$search}%")
//						->orWhere('grade', 'like', "%{$search}%")
//						->orWhere('status', 'like', "%{$search}%");
			});
		}

		// Apply grade filter
		if ($request->filled('grade')) {
			$query->where('grade', $request->grade);
		}

		// Apply status filter
		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		// Apply documents filter
		if ($request->filled('documents')) {
			$query->where('documents_verified', $request->documents);
		}

		// Apply experience range filter
		if ($request->filled('experience_min')) {
			$query->where('years_of_experience', '>=', $request->experience_min);
		}
		if ($request->filled('experience_max')) {
			$query->where('years_of_experience', '<=', $request->experience_max);
		}

		// Apply sorting
		$sortColumn = $request->get('sort', 'name');
		$sortDirection = $request->get('direction', 'asc');
		$allowedColumns = ['name', 'grade', 'status', 'years_of_experience'];

		if (in_array($sortColumn, $allowedColumns)) {
			$query->orderBy($sortColumn, $sortDirection);
		}

		$smmes = $query->paginate(10);

		return view('tenant.smmes.index', compact('smmes'));
	}


	public function create()
	{
		return view('tenant.smmes.create');
	}


	public function store(Request $request)
	{
		$request->merge([
				'documents_verified' => $request->input('documents_verified') == 'true',
		]);
		$validatedData = $request->validate([
				'name' => 'required|max:255',
				'registration_number' => 'required|string',
				'years_of_experience' => 'required|integer',
				'team_composition' => 'required',
				'documents_verified' => 'required|boolean',
				'grade' => 'required|max:255',
				'status' => 'required|in:green,yellow,red',
		]);


		Smmes::create($validatedData);

		return redirect()->route('tenant.smmes.index')->with('success', 'SMME created successfully.');
	}


	public function show(Smmes $smme)
	{
		return view('tenant.smmes.show', compact('smme'));
	}


	public function edit(Smmes $smme)
	{
		return view('tenant.smmes.edit', compact('smme'));
	}


	public function update(Request $request, Smmes $smme)
	{
		$request->merge([
				'documents_verified' => $request->input('documents_verified') == 'true',
		]);
		$validatedData = $request->validate([
				'name' => 'required|max:255',
				'registration_number' => 'required|string',
				'years_of_experience' => 'required|integer',
				'team_composition' => 'required',
				'documents_verified' => 'required|boolean',
				'grade' => 'required|max:255',
				'status' => 'required|in:green,yellow,red',
		]);
			logger($validatedData);
		$smme->update($validatedData);

		return redirect()->route('tenant.smmes.index')->with('success', 'SMME updated successfully.');
	}


	public function destroy(Smmes $smme)
	{
		$smme->delete();

		return redirect()->route('tenant.smmes.index')->with('success', 'SMME deleted successfully.');
	}

}