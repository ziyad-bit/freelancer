<?php

namespace App\Providers;

use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\ProjectRepository;
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
		$this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
		$this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
		$this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
		$this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
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
