<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class Proposal
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
		$proposal_id = $request->route('proposal');
		$user_id     = DB::table('proposals')->where('id', $proposal_id)->value('user_id');

		if ($user_id !== Auth::id()) {
			abort(500, 'something went wrong');
		}

		return $next($request);
	}
}
