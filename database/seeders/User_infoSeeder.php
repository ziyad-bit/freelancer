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

		DB::table('user_infos')->insert([
			'location'       => $faker->country(),
			'review'         => rand(1, 5),
			'job'            => $faker->jobTitle(),
			'overview'       => $faker->paragraph(),
			'payment_method' => Arr::random(['verified', 'unverified']),
			'type'           => Arr::random(['client', 'freelancer']),
			'online'         => Arr::random(['online', 'offline']),
			'user_id'        => $users->random(),
		]);
	}
}
