<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class Project
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
		$project_id = $request->route('project');
		$user_id    = DB::table('projects')->where('id', $project_id)->value('user_id');

		if ($user_id !== Auth::id()) {
			return to_route('project.fetch')->with('error', 'something went wrong');
		}

		return $next($request);
	}
}
