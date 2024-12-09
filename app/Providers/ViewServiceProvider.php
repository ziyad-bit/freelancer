<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\View\Composers\NotifsComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(NotifsComposer::class);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot(Request $request)
	{
		if (!request()->is('/admins/*') && !$request->expectsJson()) {
			View::composer('*', NotifsComposer::class);
		}
	}
}
