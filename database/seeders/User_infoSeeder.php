<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class User_infoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Factory::create();
		$users = collect(DB::table('users')->pluck('id')->toArray());

		foreach ($users as $user) {
			DB::table('user_infos')->insert([
				'location'       => $faker->country(),
				'job'            => $faker->jobTitle(),
				'overview'       => $faker->paragraph(),
				'card_num'       => rand(1000000, 5000000),
				'type'           => Arr::random(['client', 'freelancer']),
				'user_id'        => $user,
			]);
		}
	}
}
