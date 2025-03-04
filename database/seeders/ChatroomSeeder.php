<?php

namespace Database\Seeders;

use App\Models\Chatroom;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatroomSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker   = Factory::create();
		$users   = collect(DB::table('users')->pluck('id')->toArray());

		foreach ($users as $user) {
			$chatroom = Chatroom::create([
				'id'          => $faker->uuid(),
				'owner_id'    => $user,
			]);

			$users = User::inRandomOrder()->take(3)->pluck('id')->toArray();
			$chatroom->chatroom_user()->attach($users);

			Chatroom::query()
				->where('user_id',$users)
				->update(['decision'=>'approved','created_at'=>now()]);
		}
	}
}
