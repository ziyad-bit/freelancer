<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class User_skillSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 100; $i++) {
			$user = User::inRandomOrder()->first();

			$skills = Skill::inRandomOrder()->take(2)->pluck('id')->toArray();
			$user->user_skills()->attach($skills);
		}
	}
}
