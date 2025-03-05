<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker       = Factory::create();
		$users       = collect(DB::table('users')->pluck('id')->toArray());
		$chatrooms   = collect(DB::table('chat_rooms')->pluck('id')->toArray());

		foreach ($users as $user) {
			$date   = $faker->dateTimeBetween('-5 years');

			DB::table('messages')->insert([
				'text'         => encrypt($faker->sentence(3)),
				'sender_id'    => $user,
				'receiver_id'  => $users->random(),
				'chat_room_id' => $chatrooms->random(),
				'created_at'   => $date,
			]);
		}
	}
}
