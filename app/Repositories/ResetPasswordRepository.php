<?php

namespace App\Repositories;

use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\{Auth, Cache, DB};
use App\Interfaces\Repository\VerificationRepositoryInterface;
use App\Interfaces\Repository\ResetPasswordRepositoryInterface;

class ResetPasswordRepository implements ResetPasswordRepositoryInterface
{
	// updateVerify 
	public function sendLink(Request $request):void
	{
		$email= $request->safe()->__get('email');
		$token = Str::random(40);

		Cache::put('token_' . $email, $token, now()->addMinutes(30));

		Mail::to($email)->send(new ResetPassword($token,$email));
	}

	// updateVerify 
	public function updatePassword(Request $request):array
	{
		$email= $request->safe()->__get('email');
		$token= $request->safe()->__get('token');

		if (Cache::has('token_' . $email)) {
			$cached_token = Cache::get('token_' . $email);

			if ($cached_token === $token) {
				DB::table('users')
					->where('email', $email)
					->update(['password' => $request->password]);
			} else {
				abort(500, 'something went wrong');
			}
		} else {
			return ['error' => 'the link is expired'];
		}

		return ['success' => 'you updated your password successfully'];
	}
}