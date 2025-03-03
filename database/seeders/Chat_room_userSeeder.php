<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Chat_room_userSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users         = collect(DB::table('users')->pluck('id')->toArray());
		$chat_room_ids = collect(DB::table('chat_rooms')->pluck('id')->toArray());
		$faker = Factory::create();

		foreach ($users as $i => $user) {
			$date   = $faker->dateTimeBetween('-5 years');

			DB::table('chat_room_user')->insert([
				'chat_room_id'   => $chat_room_ids->random(),
				'user_id'        => $user,
				'created_at'     => $date,
			]);
		}
	}
}
