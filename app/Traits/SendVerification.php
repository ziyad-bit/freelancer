<?php

namespace App\Traits;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\{Cache, Hash, Mail};
use Illuminate\Support\Str;

trait SendVerification
{
	//MARK: sendVerification
	public function sendVerification($user):void
	{
		$hash = Hash::make(Str::random());

		Cache::add('hash_' . $user->id, $hash, now()->addMinutes(30));

		Mail::to($user)->send(new VerifyEmail($hash));
	}
}
