<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use App\Exceptions\GeneralNotFoundException;

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
		$chat_room_user = DB::table('chat_room_user')
			->where(['chat_room_id' => $request->chat_room_id, 'user_id' => Auth::id()])
			->first();

		if (!$chat_room_user) {
			throw new GeneralNotFoundException('chatroom');
		}

		return $next($request);
	}
}
