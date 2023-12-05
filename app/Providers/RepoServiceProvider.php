<?php

namespace App\Providers;

use App\Interfaces\Repository\{AuthRepositoryInterface, FileRepositoryInterface, ProfileRepositoryInterface, ProjectRepositoryInterface, ProposalRepositoryInterface, SkillRepositoryInterface};
use App\Repositories\{AuthRepository, FileRepository, ProfileRepository, ProjectRepository, ProposalRepository, SkillRepository};
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
		$this->app->bind(ProposalRepositoryInterface::class, ProposalRepository::class);
		$this->app->bind(FileRepositoryInterface::class, FileRepository::class);
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
