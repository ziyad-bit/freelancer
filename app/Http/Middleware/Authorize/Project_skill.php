<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class Project_skill
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
		$id      = request('id');
		$user_id = DB::table('project_skill')
						->join('projects', 'projects.id', '=', 'project_skill.project_id')
						->where('project_skill.id', $id)
						->value('user_id');

		if ($user_id !== Auth::id()) {
			abort(500, 'something went wrong');
		}

		return $next($request);
	}
}
