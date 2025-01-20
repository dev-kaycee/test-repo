<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smmes extends Model
{
	protected $fillable = [
			'name',
			'registration_number',
			'years_of_experience',
			'team_composition',
			'grade',
			'status',
			'documents_verified',
	];

	public function projects()
	{
		return $this->hasMany(Project::class);
	}
}