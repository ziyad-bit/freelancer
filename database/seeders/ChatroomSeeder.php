<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatroomSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker   = Factory::create();
		$users   = collect(DB::table('users')->pluck('id')->toArray());

		foreach ($users as $i => $user) {
			$date   = $faker->dateTimeBetween('-5 years');

			DB::table('chat_rooms')->insert([
				'id'          => $faker->uuid(),
				'owner_id'    => $user,
				'created_at'  => $date,
			]);
		}
	}
}
