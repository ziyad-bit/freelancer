<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Chat_room_userSeeder extends Seeder
{
	use DateRandom;
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users         = collect(DB::table('users')->pluck('id')->toArray());
		$chat_room_ids = collect(DB::table('chat_rooms')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			DB::table('chat_room_user')->insert([
				'chat_room_id'   => $chat_room_ids->random(),
				'user_id'        => $users->random(),
				'created_at'     => $date,
			]);
		}
	}
}
