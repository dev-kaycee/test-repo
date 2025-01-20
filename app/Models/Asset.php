<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

	const IN_USE = 'In use';
	const AVAILABLE = 'Not in use';
	const IN_SERVICE = 'In service';

	protected $fillable = [
			'name',
			'serial_number',
			'model',
			'status',
			'cost',
			'location',
			'purchase_date',
			'warranty_date',
			'asset_type_id',
			'project_id'
	];

	protected $casts = [
			'purchase_date' => 'date',
			'warranty_date' => 'date',
			'cost' => 'decimal:2',
	];

	public function assetType()
	{
		return $this->belongsTo(AssetType::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}
}