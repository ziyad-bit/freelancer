<?php

namespace App\Http\Middleware\Authorize;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class File
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
		$file_name = $request->route('name');
		$user_id   = DB::table('projects')
				->join('project_files', 'project_files.project_id', '=', 'projects.id')
				->where('file', $file_name)
				->value('user_id');

		if ($user_id !== Auth::id()) {
			return response()->json(['error' => 'something went wrong'], 500);
		}

		return $next($request);
	}
}
