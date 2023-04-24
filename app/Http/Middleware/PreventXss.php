<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventXss
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$inputs = $request->input();

		if ($inputs != []) {
			array_walk_recursive($inputs, function ($inputs) {
				$inputs = strip_tags($inputs);
			});

			$request->merge($inputs);
		}

		return $next($request);
	}
}
