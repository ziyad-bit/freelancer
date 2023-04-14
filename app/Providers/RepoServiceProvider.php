<?php

namespace App\Providers;

use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\ProfileRepository;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
		$this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{

	}
}
