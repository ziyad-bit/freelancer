<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class VerifyEmail 
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
		if (Auth::user()->email_verified_at === null) {
			if (!$request->expectsJson()) {
				return to_route('verification.get');
			}else{
				return abort(403,'you should verify your email');
			}
		}

		return $next($request);
	}
}
