<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
	protected $fillable = [
			'quote_id',
			'description',
			'quantity',
			'unit_price',
			'vat_rate',
			'amount',
	];

	// Define the inverse relationship to Quote
	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}
}