<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class Transaction_debate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request                                                                          $request
	 * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$users_id  = DB::table('debates')
			->select('initiator_id','opponent_id')
			->where('id', $request->debate)
			->first();

		$users_id = (array) $users_id;

		if (!in_array($request->receiver_id, $users_id)) {
			abort(500, 'something went wrong');
		} 

		return $next($request);
	}
}
