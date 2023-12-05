<?php

namespace App\Providers;

use App\Classes\AbstractFactory\{ApplicationFile, FileAbstractFactory, Image, Video};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(ApplicationFile::class);
		$this->app->singleton(Image::class);
		$this->app->singleton(Video::class);
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
