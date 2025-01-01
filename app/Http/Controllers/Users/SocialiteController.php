<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\{Auth, DB, Hash};
use App\Interfaces\Repository\SocialiteRepositoryInterface;

class SocialiteController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback(SocialiteRepositoryInterface $socialiteRepository ,string $provider )
    {
        $socialiteRepository->callback($provider);

        return to_route('home');
    }
}
