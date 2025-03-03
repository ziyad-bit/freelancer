<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users    = collect(DB::table('users')->pluck('id')->toArray());
		$projects = collect(DB::table('projects')->pluck('id')->toArray());
		$faker = Factory::create();

		foreach ($users as  $user) {
			$date   = $faker->dateTimeBetween('-5 years');

			DB::table('reviews')->insert([
				'rate'        => rand(1, 5),
				'giver_id'    => $users->random(),
				'receiver_id' => $user,
				'project_id'  => $projects->random(),
				'created_at'  => $date,
			]);
		}
	}
}
