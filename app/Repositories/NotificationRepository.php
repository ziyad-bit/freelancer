<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repository\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{
	// MARK: update
	public function update(): void
	{	
		Log::info("user read notifications");

		User::find(Auth::id())->unreadNotifications()->update(['read_at' => now()]);
	}

	// MARK: show_old_notifs
	public function show_old_notifs(string $created_at): string
	{
		$user_notifs = User::find(Auth::id())
			->notifications()
			->where('created_at', '<', $created_at)
			->take(5)
			->get();

		Log::info("user get old notifications");

		return view('users.includes.notifications.index', compact('user_notifs'))->render();
	}
}
