<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Skill
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param   int $id
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next, $id)
	{
		//delete
		$user_id = DB::table('user_skill')->where('id', $id)->value('user_id');
		if ($user_id !== Auth::id()) {
			return response()->json(['error' => 'something went wrong'], 500);
		}

		return $next($request);
	}
}
