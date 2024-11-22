<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\{Arr, Str};

class TransactionSeeder extends Seeder
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
		$projects  = collect(DB::table('projects')->pluck('id')->toArray());

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();

			$type = Arr::random(['withdraw', 'release', 'milestone']);

			DB::table('transactions')->insert([
				'amount'      => rand(100, 200),
				'type'        => $type,
				'trans_id'    => Str::random(10),
				'owner_id'    => $users->random(),
				'project_id'  => $type == 'withdraw' ? null : $projects->random(),
				'receiver_id' => $type == 'withdraw' ? null : $users->random(),
				'created_at'  => $date,
				'updated_at'  => $date,
			]);
		}
	}
}
