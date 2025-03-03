<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class proposalSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker    = Factory::create();
		$users    = collect(DB::table('users')->pluck('id')->toArray());
		$projects = collect(DB::table('projects')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $faker->dateTimeBetween('-5 years');

			DB::table('proposals')->insert([
				'content'     => $faker->paragraph(),
				'price'       => $faker->numberBetween(100, 200),
				'approval'    => Arr::random(['approved', 'refused', 'pending']),
				'finished'    => Arr::random(['finished', 'unfinished', null, 'in progress']),
				'num_of_days' => rand(3, 9),
				'user_id'     => $users->random(),
				'project_id'  => $projects->random(),
				'created_at'  => $date,
				'updated_at'  => $date,
			]);
		}
	}
}
