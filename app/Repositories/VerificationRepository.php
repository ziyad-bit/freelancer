<?php

namespace App\Repositories;

use App\Interfaces\Repository\VerificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB};

class VerificationRepository implements VerificationRepositoryInterface
{
	// logout   #####################################
	public function updateVerify(Request $request):array
	{
		$auth_id = Auth::id();

		if (Cache::has('hash_' . $auth_id) && Auth::user()->email_verified_at === null) {
			$hash = Cache::get('hash_' . $auth_id);

			if ($hash === request('hash')) {
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
