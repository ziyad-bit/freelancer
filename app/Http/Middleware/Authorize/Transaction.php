<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class Transaction
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
		$transaction_id = $request->id;

		$user_id  = DB::table('transactions')->where('id', $transaction_id)->value('owner_id');

		if ($user_id !== Auth::id()) {
			return to_route('transaction.index')->with('error', 'something went wrong');
		}

		return $next($request);
	}
}
