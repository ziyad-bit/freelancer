<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	use HasFactory;

	protected $guarded = [];

	public function project_skills()
	{
		return $this->belongsToMany('App\Models\Skill', 'App\Models\Project_skill', 'project_id', 'skill_id');
	}
}
