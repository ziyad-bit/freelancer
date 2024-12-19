<?php

namespace App\Traits;

use App\Mail\VerifyEmail;
use Illuminate\Support\{Str};
use Illuminate\Http\{Request};
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\{Cache, Hash, Log, Mail, Storage};

trait SendVerification
{
	//MARK: sendVerification
	public function sendVerification($user):void
	{
		$hash = Hash::make(Str::random());
		
		Cache::add('hash_'.$user->id,$hash,now()->addMinutes(30));

		Mail::to($user)->send(new VerifyEmail($hash));
	}
}
