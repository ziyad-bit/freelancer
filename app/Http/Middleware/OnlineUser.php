<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class OnlineUser
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request                                                                          $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$auth_id = Auth::id();
		Cache::add('online_' . $auth_id, $auth_id, now()->addMinutes(4));

		return $next($request);
	}
}
