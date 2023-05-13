<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Project;
use Illuminate\Database\Seeder;

class Project_skillSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$projects = Project::all();
		foreach ($projects as $project) {
			$skills = Skill::inRandomOrder()->take(2)->pluck('id')->toArray();
			$project->Project_skills()->attach($skills);
		}
	}
}
