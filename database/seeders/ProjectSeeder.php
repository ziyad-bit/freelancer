<?php

namespace Database\Seeders;

use App\Models\{Project, skill};
use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
	use DateRandom;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Factory::create();
		$users = collect(DB::table('users')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			$project = Project::create([
				'title'      => $faker->title(),
				'content'    => $faker->paragraph(),
				'user_id'    => $users->random(),
				'created_at' => $date,
				'updated_at' => $date,
			]);

			$skills = Skill::inRandomOrder()->take(2)->pluck('id')->toArray();
			$project->project_skills()->attach($skills);
		}
	}
}
