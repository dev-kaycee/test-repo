<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	/**
	 * @var mixed
	 */
	protected $fillable = [
			'client_name',
			'client_address',
			'issue_date',
			'expiry_date',
			'invoice_number',
			'vat_number',
			'company_name',
			'company_address',
			'total_amount',
	];

	protected $casts = [
			'issue_date' => 'date',
			'expiry_date' => 'date',
			'total_amount' => 'decimal:2',
	];

	public function items()
	{
		return $this->hasMany(InvoiceItem::class);
	}
}