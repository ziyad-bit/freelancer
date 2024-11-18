<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$skills   = ['html', 'video editor', 'data entry', 'css', 'js'];

		foreach ($skills as $skill) {
			DB::table('skills')->insert([
				'skill'       => $skill,
			]);
		}
	}
}
