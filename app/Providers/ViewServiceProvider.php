<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\View\Composers\NotifsComposer;
use Illuminate\Support\Facades\{View};
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

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
