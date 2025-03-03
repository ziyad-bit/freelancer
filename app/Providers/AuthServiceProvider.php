<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		LogViewer::auth(function ($request) {
			return $request->user() && in_array($request->user()->email, [
				'grimes.jaron@example.org',
				'ziyad199523@gmail.com',
				'tyra.casper@example.com',
			]);
		});
	}
}
