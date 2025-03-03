<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$users     = collect(DB::table('users')->pluck('id')->toArray());
		$faker     = Factory::create();

		foreach ($users as $user) {
			$date        = $faker->dateTimeBetween('-5 years');
			$sender_id   = $users->random();
			$sender      = DB::table('users')
					->select('name', 'image')
					->where('id', $sender_id)
					->first();

			$data  = [
				'text'         => encrypt($faker->sentence),
				'sender_id'    => $sender_id,
				'sender_image' => $sender->image,
				'sender_name'  => $sender->name,
			];

			DB::table('notifications')->insert([
				'id'              => $faker->uuid(),
				'read_at'         => $date,
				'type'            => 'message',
				'notifiable_id'   => $user,
				'notifiable_type' => 'App\Models\User',
				'data'            => json_encode($data),
				'created_at'      => $date,
			]);
		}
	}
}
