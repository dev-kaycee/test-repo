<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;


	const STATUS_PLANNED = 1;
	const STATUS_IN_PROGRESS = 2;
	const STATUS_COMPLETED = 3;

	protected $fillable = [
			'project_name',
			'project_type_id',
			'team_leader_user_id',
			'location_id',
			'smme_id',
			'quote_id',
			'description',
			'startDate',
			'endDate',
			'planned_days',
			'budget',
			'actual_budget',
			'status',
			'target_hectares',
			'actual_hectares',
			'hectares_per_day',
			'smme',
			'vehicle_kms_target',
			'actual_vehicle_kms',
			'number_of_students',
			'quote_check',
			'invoice_check',
			'labour_report_check',
			'safety_talk_check',
			'herbicide_check',
			'inspection_check',
			'facilitation_check',
			'assessment_check',
			'moderation_check',
			'database_admin_check',
			'certification_check',
	];

	protected $dates = ['deleted_at'];

	public function projectType()
	{
		return $this->belongsTo(\App\Models\ProjectType::class);
	}

	public function teamLeader()
	{
		return $this->belongsTo(\App\Models\User::class, 'team_leader_user_id');
	}

	public function location()
	{
		return $this->belongsTo(\App\Models\Location::class);
	}

	public function smme()
	{
		return $this->belongsTo(\App\Models\Smmes::class);
	}

	public function quote()
	{
		return $this->belongsTo(\App\Models\Quote::class);
	}

	public function assets()
	{
		return $this->hasMany(Asset::class);
	}
}