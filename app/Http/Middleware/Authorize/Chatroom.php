<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Chatroom
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request                                                                          $request
	 * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\|Illuminate\Http\JsonResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$receiver_id   = $request->route('receiver_id');
		$receiver_type = DB::table('user_infos')
					->where('user_id', $receiver_id)
					->value('type');

		// user can't start chat with any client
		if ($receiver_type !== 'freelancer') {
			abort(500, 'something went wrong');
		}

		return $next($request);
	}
}
