<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
	use DateRandom;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users     = collect(DB::table('users')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			DB::table('notifications')->insert([
				'read_at'     => $date,
				'type'        => Arr::random(['message', 'add_user_to_chat']),
				'sender_id'   => $users->random(),
				'receiver_id' => $users->random(),
				'created_at'  => $date,
			]);
		}
	}
}
