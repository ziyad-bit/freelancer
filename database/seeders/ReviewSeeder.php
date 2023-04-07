<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
	use DateRandom;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users = collect(DB::table('users')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			DB::table('reviews')->insert([
				'rate'        => rand(1, 5),
				'giver_id'    => $users->random(),
				'receiver_id' => $users->random(),
				'created_at'  => $date,
			]);
		}
	}
}
