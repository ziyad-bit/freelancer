<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\AuthProjectRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Interfaces\Repository\Admins\UserRepositoryInterface;
use App\Repositories\{AuthRepository, ChatRoomInvitationRepository, ChatRoomRepository, FileRepository, MessageRepository, NotificationRepository, ProfileRepository, ProjectRepository, ProposalRepository, ResetPasswordRepository, SearchRepository, SkillRepository, SocialiteRepository, TransactionRepository, VerificationRepository};
use App\Interfaces\Repository\{AuthProjectRepositoryInterface, AuthRepositoryInterface, ChatRoomInvitationRepositoryInterface, ChatRoomRepositoryInterface, FileRepositoryInterface, MessageRepositoryInterface, NotificationRepositoryInterface, ProfileRepositoryInterface, ProjectRepositoryInterface, ProposalRepositoryInterface, ResetPasswordRepositoryInterface, SearchRepositoryInterface, SkillRepositoryInterface, SocialiteRepositoryInterface, TransactionRepositoryInterface, UserRepositoryInterface as RepositoryUserRepositoryInterface, VerificationRepositoryInterface};
use App\Interfaces\Repository\Admins\AdminRepositoryInterface;
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use App\Interfaces\Repository\Admins\ProjectRepositoryInterface as AdminsProjectRepositoryInterface;
use App\Repositories\Admins\AdminRepository;
use App\Repositories\Admins\DebateRepository;
use App\Repositories\Admins\ProjectRepository as AdminsProjectRepository;
use App\Repositories\Admins\UserRepository;

class RepoServiceProvider extends ServiceProvider implements DeferrableProvider
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
		$this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
		$this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
		$this->app->bind(ChatRoomRepositoryInterface::class, ChatRoomRepository::class);
		$this->app->bind(SearchRepositoryInterface::class, SearchRepository::class);
		$this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
		$this->app->bind(VerificationRepositoryInterface::class, VerificationRepository::class);
		$this->app->bind(ResetPasswordRepositoryInterface::class, ResetPasswordRepository::class);
		$this->app->bind(ChatRoomInvitationRepositoryInterface::class, ChatRoomInvitationRepository::class);
		$this->app->bind(SocialiteRepositoryInterface::class, SocialiteRepository::class);
		$this->app->bind(AuthProjectRepositoryInterface::class, AuthProjectRepository::class);

		//MARK:admins
		$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
		$this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
		$this->app->bind(AdminsProjectRepositoryInterface::class,AdminsProjectRepository::class);
		$this->app->bind(DebateRepositoryInterface::class,DebateRepository::class);
	}


	public function provides():array
	{
		return [
			AuthRepositoryInterface::class,
			ProfileRepositoryInterface::class,
			SkillRepositoryInterface::class,
			ProjectRepositoryInterface::class,
			ProposalRepositoryInterface::class,
			FileRepositoryInterface::class,
			MessageRepositoryInterface::class,
			NotificationRepositoryInterface::class,
			ChatRoomRepositoryInterface::class,
			SearchRepositoryInterface::class,
			TransactionRepositoryInterface::class,
			VerificationRepositoryInterface::class,
			ResetPasswordRepositoryInterface::class,
			ChatRoomInvitationRepositoryInterface::class,
			SocialiteRepositoryInterface::class,
			AuthProjectRepositoryInterface::class,

			//MARK:admins
			UserRepositoryInterface::class,
			AdminRepositoryInterface::class,
			AdminsProjectRepositoryInterface::class,
			DebateRepositoryInterface::class,
		];
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
