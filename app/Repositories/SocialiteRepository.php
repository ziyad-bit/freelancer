<?php

namespace App\Repositories;

use App\Http\Requests\ProposalRequest;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\{Auth, DB, Hash};
use App\Exceptions\GeneralNotFoundException;
use App\Interfaces\Repository\ProposalRepositoryInterface;
use App\Interfaces\Repository\SocialiteRepositoryInterface;

class SocialiteRepository implements SocialiteRepositoryInterface
{
	//MARK: callback
	public function callback(string $provider): void
	{
		$provider_user = Socialite::driver($provider)->user();
		$user_id     = DB::table('users')->where('email', $provider_user->email)->value('id');

		if (!$user_id) {
			$user_id = DB::table('users')
				->insertGetId([
					'name'              => $provider_user->name,
					'email'             => $provider_user->email,
					'email_verified_at' => now(),
					'password'          => Hash::make($provider_user->id),
				]);
		} 

		Auth::loginUsingId($user_id);
	}
}