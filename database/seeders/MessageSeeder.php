<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
	use DateRandom;
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

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			DB::table('messages')->insert([
				'text'         => encrypt($faker->sentence(3)),
				'sender_id'    => $users->random(),
				'receiver_id'  => $users->random(),
				'chat_room_id' => $chatrooms->random(),
				'created_at'   => $date,
			]);
		}
	}
}
