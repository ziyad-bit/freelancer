<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\SocialiteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback(SocialiteRepositoryInterface $socialiteRepository, string $provider)
	{
		$socialiteRepository->callback($provider);

		return to_route('profile.index', Auth::user()->slug);
	}
}
