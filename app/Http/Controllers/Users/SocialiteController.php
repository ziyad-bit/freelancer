<?php

namespace App\Http\Controllers\Users;

use Vonage\Client;
use Vonage\SMS\Message\SMS;
use App\Http\Controllers\Controller;
use Vonage\Client\Credentials\Basic;
use Laravel\Socialite\Facades\Socialite;
use App\Interfaces\Repository\SocialiteRepositoryInterface;
use Illuminate\Http\Request;

class SocialiteController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback(SocialiteRepositoryInterface $socialiteRepository, string $provider)
	{
		$socialiteRepository->callback($provider);

		return to_route('home');
	}
}
