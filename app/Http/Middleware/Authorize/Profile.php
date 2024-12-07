<?php

namespace App\Http\Middleware\Authorize;

use App\Interfaces\Repository\ProfileRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Profile
{
	private $profileRepository;

	public function __construct(ProfileRepositoryInterface $profileRepository)
	{
		$this->profileRepository = $profileRepository;
	}

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
		$user_info = DB::table('user_infos')->find(Auth::id(),'id');
		if ($user_info ) {
			return to_route('profile.index')->with('error', 'something went wrong');
		}

		return $next($request);
	}
}
