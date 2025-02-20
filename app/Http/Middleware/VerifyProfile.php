<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyProfile
{
	/**
	 * Get the path the user should be redirected to when they are not authenticated.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return string|null
	 */
	public function handle(Request $request, Closure $next):mixed
	{
		$user = Auth::user();

		if ($user->profile_verified_at === null) {
			if (!$request->expectsJson()) {
				return to_route('profile.get', $user->slug)->with('error', 'you should verify your profile');
			} else {
				return abort(403, 'you should verify your profile');
			}
		}

		return $next($request);
	}
}
