<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Quote;
use App\Models\QuoteItem;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$query = Quote::query();

		// Apply search filter
		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function ($q) use ($search) {
				$q->where('client_name', 'like', "%{$search}%");
				//->where('quote_number', 'like', "%{$search}%");
			});
		}
		// Apply the total amount filter
		if ($request->filled('total_amount_range')) {
    		$range = $request->total_amount_range;

    	if ($range === '150000+') {
        // For amounts greater than 150000
        	$query->where('total_amount', '>', 150000);
    	} else {
        // For other ranges
        	list($min, $max) = explode('-', $range);
        	$query->whereBetween('total_amount', [$min, $max]);
    	}
		}
		// Filter by Preset Date Ranges
		if ($request->filled('issue_date')) {
			$preset = $request->issue_date;
			if ($preset == 'last_7_days') {
				$query->whereBetween('issue_date', [now()->subDays(7), now()]);
			} elseif ($preset == 'last_30_days') {
				$query->whereBetween('issue_date', [now()->subDays(30), now()]);
			} elseif ($preset == 'last_6_months') {
				$query->whereBetween('issue_date', [now()->subMonths(6), now()]);
			} elseif ($preset == 'last_year') {
				$query->whereBetween('issue_date', [now()->subYear(), now()]);
			}
		}
		// Filter by Preset Expiry Date Ranges
		if ($request->filled('expiry_date')) {
    	$preset = $request->expiry_date;

    	if ($preset == 'next_7_days') {
        	$query->whereBetween('expiry_date', [now(), now()->addDays(7)]);
    	} elseif ($preset == 'next_30_days') {
        	$query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
    	} elseif ($preset == 'next_6_months') {
        	$query->whereBetween('expiry_date', [now(), now()->addMonths(6)]);
    	} elseif ($preset == 'next_year') {
        	$query->whereBetween('expiry_date', [now(), now()->addYear()]);
    	}
		}
		// Apply sorting
		$sortColumn = $request->get('sort', 'client_name');
		$sortDirection = $request->get('direction', 'asc');
		$allowedColumns = ['client_name', 'issue_date', 'expiry_date', 'total_amount'];

		if (in_array($sortColumn, $allowedColumns)) {
			$query->orderBy($sortColumn, $sortDirection);
		}
		

		$quotes = $query->latest()->paginate(10);
		return view('tenant.quotes.index', compact('quotes'));
	}

	public function create()
	{
		if (!Gate::allows('has_permission','can_create_quotes')) {
			return view('errors.403');
		}

		$nextQuoteNumber = $this->generateNextQuoteNumber();
		return view('tenant.quotes.create', compact('nextQuoteNumber'));
	}


	public function store(Request $request)
	{
		$validatedData = $request->validate([
				'client_name' => 'required|string',
				'client_address' => 'required|string',
				'issue_date' => 'required|date',
				'expiry_date' => 'required|date',
				'quote_number' => 'required|string|unique:quotes',
				'vat_number' => 'required|string',
				'company_name' => 'required|string',
				'total_amount' => 'required|numeric|min:0',
				'company_address' => 'required|string',
				'items' => 'required|array',
				'items.*.description' => 'required|string',
				'items.*.quantity' => 'required|integer|min:1',
				'items.*.unit_price' => 'required|numeric|min:0',
				'items.*.vat_rate' => 'required|numeric|min:0',
		]);

		$quote = Quote::create($validatedData);

		$totalAmount = 0;
		foreach ($validatedData['items'] as $item) {
			$amount = $item['quantity'] * $item['unit_price'] * (1 + $item['vat_rate'] / 100);
			$totalAmount += $amount;

			QuoteItem::create([
					'quote_id' => $quote->id,
					'description' => $item['description'],
					'quantity' => $item['quantity'],
					'unit_price' => $item['unit_price'],
					'vat_rate' => $item['vat_rate'],
					'amount' => $amount,
			]);
		}

		$quote->update(['total_amount' => $totalAmount]);

		return redirect()->route('tenant.quotes.create', $quote)->with('success', 'Quote created successfully.');
	}

	public function show(Quote $quote)
	{if (!Gate::allows('has_permission','can_view_quotes')) {
		return view('errors.403');
	}
		$quote->load('items'); // Ensure quote items are loaded
		return view('tenant.quotes.show', compact('quote'));
	}

	public function edit(Quote $quote)
	{
		if (!Gate::allows('has_permission','can_edit_quotes')) {
			return view('errors.403');
		}
		$quote->load('items'); // Ensure quote items are loaded
		return view('tenant.quotes.edit', compact('quote'));
	}

	public function update(Request $request, Quote $quote)
	{
		$validatedData = $request->validate([
				'client_name' => 'required|string',
				'client_address' => 'required|string',
				'issue_date' => 'required|date',
				'expiry_date' => 'required|date',
				'quote_number' => 'required|string|unique:quotes,quote_number,' . $quote->id,
				'vat_number' => 'required|string',
				'company_name' => 'required|string',
				'company_address' => 'required|string',
				'items' => 'required|array',
				'items.*.description' => 'required|string',
				'items.*.quantity' => 'required|integer|min:1',
				'items.*.unit_price' => 'required|numeric|min:0',
				'items.*.vat_rate' => 'required|numeric|min:0',
				'items.*.amount' => 'required|numeric|min:0',
		]);

		$quote->update($validatedData);

		// Delete existing items
		$quote->items()->delete();

		// Create new items
		$totalAmount = 0;
		foreach ($validatedData['items'] as $item) {
			QuoteItem::create([
					'quote_id' => $quote->id,
					'description' => $item['description'],
					'quantity' => $item['quantity'],
					'unit_price' => $item['unit_price'],
					'vat_rate' => $item['vat_rate'],
					'amount' => $item['amount'],
			]);
			$totalAmount += $item['amount'];
		}

		// Update total amount
		$quote->update(['total_amount' => $totalAmount]);

		return redirect()->route('tenant.quotes.show', $quote)->with('success', 'Quote updated successfully.');
	}


	public function destroy(Quote $quote)
	{
		if (!Gate::allows('has_permission','can_delete_quotes')) {
			return view('errors.403');
		}
		$quote->delete();
		return redirect()->route('tenant.quotes.index')->with('success', 'Quote deleted successfully.');
	}

	private function generateNextQuoteNumber()
	{
		$lastQuote = Quote::latest()->first();
		if (!$lastQuote) {
			return 'Q0001';
		}

		$lastNumber = intval(substr($lastQuote->quote_number, 1));
		$nextNumber = $lastNumber + 1;
		return 'Q' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
	}

	public function generatePDF(Quote $quote, PDF $pdf)
	{
		$html = view('tenant.quotes.pdf', compact('quote'))->render();

		$pdf->loadHTML($html);

		$pdf->setPaper('A4', 'portrait');

		return $pdf->stream('quote_' . $quote->quote_number . '.pdf');
	}
}