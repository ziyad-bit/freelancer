<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatroomSeeder extends Seeder
{
	use DateRandom;
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker   = Factory::create();
		$users   = collect(DB::table('users')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			DB::table('chat_rooms')->insert([
				'id'=>$faker->uuid(),
				'owner_id'   => $users->random(),
				'created_at'  => $date,
			]);
		}
	}
}
