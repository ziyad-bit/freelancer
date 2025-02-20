<?php

namespace App\Providers;

use App\View\Composers\NotifsComposer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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

		Paginator::useBootstrapFive();
	}
}
