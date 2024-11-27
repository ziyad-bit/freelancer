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
				'card_num'       => rand(1, 5),
				'type'           => Arr::random(['client', 'freelancer']),
				'online'         => Arr::random(['online', 'offline']),
				'user_id'        => $user,
			]);
		}
	}
}
