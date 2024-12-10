<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Logging
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request                                                                          $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		$user_ip = $request->ip();

		if (Auth::check()) {
			$user_data = [
				'user-id' => Auth::id(),
				'user-name' => Auth::user()->name,
				'user-ip' => $user_ip,
			];
		}else{
			$user_data = [
				'user-ip' => $user_ip,
			];
		}

		$user_data += [
					'method' => $request->method(),
					'url' => $request->fullUrl(),
					'body' => $request->except(['password', 'password_confirmation','_token','text']),
				];

		Log::shareContext($user_data);
		
		return $next($request);
	}

	/**
     * Perform any final actions for the request lifecycle.
     *
    * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
		$user_ip = $request->ip();

		if (Auth::check()) {
			$user_data = [
				'user-id' => Auth::id(),
				'user-name' => Auth::user()->name,
				'user-ip' => $user_ip
			];
		}else{
			$user_data = [
				'user-ip' => $user_ip,
			];
		}

		$user_data += [
				'method' => $request->method(),
				'url' => $request->fullUrl(),
				'body' => $request->except(['password', 'password_confirmation','_token','text']),
				'seconds'=>microtime(true) - LARAVEL_START,
				'code'=>$response->getStatusCode(),
				'error'=>$request->session()->get('error'),
				'success'=>$request->session()->get('success')
			];

        Log::debug('logging data for every request',$user_data);
    }
}
