<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{Auth, Log};

class Logging
{
	protected static $user_data;

	public function handle(Request $request, Closure $next):mixed
	{
		$user_ip = $request->ip();

		if (Auth::check()) {
			self::$user_data = [
				'user_id'   => Auth::id(),
				'user_name' => Auth::user()->name,
				'user_ip'   => $user_ip,
			];
		} else {
			self::$user_data = [
				'user_ip' => $user_ip,
			];
		}

		$user_data = [
			'method' => $request->method(),
			'url'    => $request->fullUrl(),
			'body'   => $request->except(['password', 'password_confirmation', '_token', 'text']),
		] + self::$user_data;

		Log::withContext($user_data);

		return $next($request);
	}

	/**
	 * Handle tasks after the response has been sent to the browser.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Http\Response  $response
	 *
	 * @return void
	 */

	public function terminate($request, $response):void
	{
		$status_code = $response->getStatusCode();

		$user_data = [
			'method'  => $request->method(),
			'url'     => $request->fullUrl(),
			'body'    => $request->except(['password', 'password_confirmation', '_token', 'text']),
			'seconds' => microtime(true) - LARAVEL_START,
			'code'    => $status_code,
			'error'   => $request->session()->get('error'),
			'success' => $request->session()->get('success'),
		] + self::$user_data;

		if ($status_code === 200) {
			Log::info('successful request', $user_data);
		} elseif ($status_code >= 300 && $status_code < 400) {
			Log::debug('redirect request', $user_data);
		} elseif ($status_code >= 400 && $status_code < 500) {
			Log::error('client error', $user_data);
		} elseif ($status_code >= 500) {
			Log::critical('server error', $user_data);
		} else {
			Log::debug('unknown', $user_data);
		}
	}
}
