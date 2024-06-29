<?php

namespace App\Providers;

use App\Classes\AbstractFactory\{ApplicationFile, Photo, Video};
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(ApplicationFile::class);
		$this->app->singleton(Photo::class);
		$this->app->singleton(Video::class);
	}

	public function provides():array
	{
		return [
			ApplicationFile::class,
			Photo::class,
			Video::class,
		];
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
