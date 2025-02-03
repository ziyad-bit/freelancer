<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Project_infoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker    = Factory::create();
		$projects = collect(DB::table('projects')->pluck('id')->toArray());

		foreach ($projects as $project) {
			DB::table('project_infos')->insert([
				'min_price'   => $faker->numberBetween(100, 200),
				'max_price'   => $faker->numberBetween(200, 300),
				'num_of_days' => rand(1, 30),
				'exp'         => Arr::random(['beginner', 'intermediate', 'experienced']),
				'project_id'  => $project,
			]);
		}
	}
}
