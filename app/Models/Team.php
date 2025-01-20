<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 *
 * @package App
 * @property string $name
*/
class Team extends Model
{
    protected $fillable = ['name'];
    protected $hidden = [];
    
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
}