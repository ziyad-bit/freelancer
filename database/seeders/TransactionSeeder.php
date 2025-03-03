<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\{Arr, Str};

class TransactionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users     = collect(DB::table('users')->pluck('id')->toArray());
		$projects  = collect(DB::table('projects')->pluck('id')->toArray());
		$faker = Factory::create();

		foreach ($users as $user) {
			$date   = $faker->dateTimeBetween('-5 years');

			$type = Arr::random(['withdraw', 'release', 'milestone']);

			DB::table('transactions')->insert([
				'amount'      => rand(100, 200),
				'type'        => $type,
				'owner_id'    => $user,
				'project_id'  => $type == 'withdraw' ? null : $projects->random(),
				'receiver_id' => $type == 'withdraw' ? null : $users->random(),
				'created_at'  => $date,
				'updated_at'  => $date,
				'id'          => Str::uuid(),
			]);
		}
	}
}
