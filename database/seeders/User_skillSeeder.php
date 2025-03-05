<?php

namespace Database\Seeders;

use App\Models\{Skill, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User_skillSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users  = collect(DB::table('users')->pluck('id')->toArray());

		foreach ($users as $user) {
			$user = User::where('id', $user)->first();

			$skills = Skill::inRandomOrder()->take(2)->pluck('id')->toArray();
			$user->user_skills()->attach($skills);
		}
	}
}
