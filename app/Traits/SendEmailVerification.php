<?php

namespace App\Traits;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\{Cache, Mail};
use Illuminate\Support\Str;

trait SendEmailVerification
{
	//MARK: sendEmailVerification
	public function sendEmailVerification($email, $user_id):void
	{
		$token = Str::random(40);

		Cache::put('token_' . $user_id, $token, now()->addMinutes(30));

		Mail::to($email)->send((new VerifyEmail($token)));
	}
}
