<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Logging
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
		$url = $request->fullUrl();

		if (Auth::check()) {
			Log::shareContext([
				'user-id' => Auth::id(),
				'user-name' => Auth::user()->name,
				'user-ip' => $request->ip(),
				'url' => $url,
			]);
		}else[
			Log::shareContext([
				'user-ip' => $request->ip(),
				'url' => $url,
			])
		];
		
		return $next($request);
	}
}
