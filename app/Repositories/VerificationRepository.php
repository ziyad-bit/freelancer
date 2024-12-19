<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\{Str};
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Interfaces\Repository\VerificationRepositoryInterface;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\{Auth, Cache, DB, Hash};

class VerificationRepository implements VerificationRepositoryInterface
{
	// logout   #####################################
	public function updateVerify(Request $request):?RedirectResponse
	{
		$auth_id= Auth::id();
		
		if (Cache::has('hash_'.$auth_id) && Auth::user()->email_verified_at === null) {
			$hash=Cache::get('hash_'.$auth_id);

			if ($hash === request('hash') ) {
				DB::table('users')
				->where('id',$auth_id)
				->update(['email_verified_at'=>now()]);
			}else{
				return to_route('verification.get')->with('error','something went wrong');
			}
		}else{
			return to_route('verification.get')->with('error','the verification link is expired');
		}

		return null;
	}
}
