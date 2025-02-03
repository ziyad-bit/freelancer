<?php

namespace App\Repositories;

use App\Interfaces\Repository\VerificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB};

class VerificationRepository implements VerificationRepositoryInterface
{
	// updateVerify 
	public function updateVerify(Request $request):array
	{
		$auth_id = Auth::id();

		if (Cache::has('token_' . $auth_id) && Auth::user()->email_verified_at === null) {
			$token = Cache::get('token_' . $auth_id);

			if ($token === request('token')) {
				DB::table('users')
				->where('id', $auth_id)
				->update(['email_verified_at' => now()]);
			} else {
				abort(500, 'something went wrong');
			}
		} else {
			return ['error' => 'the verification link is expired'];
		}

		return ['success' => 'you verified your email successfully'];
	}
}
