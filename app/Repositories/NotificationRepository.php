<?php

namespace App\Repositories;

use App\Interfaces\Repository\NotificationRepositoryInterface;
use App\Models\User;
use App\Traits\DatabaseCache;
use Illuminate\Support\Facades\Auth;

class NotificationRepository implements NotificationRepositoryInterface
{
	use DatabaseCache;

	// MARK: update
	public function update(): void
	{
		$auth_id = Auth::id();
		
		User::find($auth_id)->unreadNotifications()->update(['read_at' => now()]);

		$this->forgetCache($auth_id);
	}

	// MARK: show_old_notifs
	public function show_old_notifs(string $created_at): string
	{
		$user_notifs = User::find(Auth::id())
			->notifications()
			->where('created_at', '<', $created_at)
			->take(5)
			->get();

		return view('users.includes.notifications.index', compact('user_notifs'))->render();
	}
}
