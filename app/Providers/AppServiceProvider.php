<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Classes\AbstractFactory\{ApplicationFile, Photo, Video};
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		
	}
}
