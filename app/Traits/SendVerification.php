<?php

namespace App\Traits;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\{Cache, token, Mail};
use Illuminate\Support\Str;

trait SendVerification
{
	//MARK: sendVerification
	public function sendVerification($user):void
	{
		$token = Str::random(40);

		Cache::put('token_' . $user->id, $token, now()->addMinutes(30));

		Mail::to($user)->send((new VerifyEmail($token)));
	}
}
