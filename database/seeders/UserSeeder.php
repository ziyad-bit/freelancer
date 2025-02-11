<?php

namespace Database\Seeders;

use App\Traits\DateRandom;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
	use DateRandom;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Factory::create();

		for ($i = 0; $i < 100; $i++) {
			$date   = $this->dateRandom();
			$name   = $faker->name();

			DB::table('users')->insert([
				'name'              => $name,
				'slug'              => Str::slug($name) . '-' . $i,
				'email'             => $faker->unique()->safeEmail(),
				'email_verified_at' => now(),
				'password'          => Hash::make('13131313'),
				'remember_token'    => Str::random(10),
				'image'             => 'Unknown-person.gif',
				'created_at'        => $date,
				'updated_at'        => $date,
			]);
		}
	}
}
