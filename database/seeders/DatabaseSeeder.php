<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		/* $this->call(UserSeeder::class); */
		/* $this->call(User_infoSeeder::class); */
		 	/* $this->call(ProjectSeeder::class); */
			$this->call(Project_infoSeeder::class); 
		// $this->call(SkillSeeder::class);
		/*   $this->call(User_skillSeeder::class);
		$this->call(proposalSeeder::class); */
		// $this->call(TransactionSeeder::class);
		/* $this->call(MessageSeeder::class);
		$this->call(NotificationSeeder::class);
		$this->call(ReviewSeeder::class); */

		/* $this->call(Project_skillSeeder::class); */
	}
}
