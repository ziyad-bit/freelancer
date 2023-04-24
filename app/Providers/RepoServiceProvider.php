<?php

namespace App\Providers;

use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SkillRepository;
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
		$this->app->singleton(AuthRepositoryInterface::class, AuthRepository::class);
		$this->app->singleton(ProfileRepositoryInterface::class, ProfileRepository::class);
		$this->app->singleton(SkillRepositoryInterface::class, SkillRepository::class);
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
