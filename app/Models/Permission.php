<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $fillable = ['name', 'slug'];

	/**
	 * Users with this permission
	 */
	public function users()
	{
		return $this->belongsToMany(User::class, 'user_permissions');
	}
}