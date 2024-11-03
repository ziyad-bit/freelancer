<?php

namespace App\Repositories;

use App\Interfaces\Repository\NotificationRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\{Auth};

class NotificationRepository implements NotificationRepositoryInterface
{
	// MARK: update
	public function update(): void
	{
		User::find(Auth::id())->unreadNotifications->update(['read_at' => now()]);
	}

	// MARK: show_old_notifs
	public function show_old_notifs(string $created_at): string
	{
		$user_notifs = User::find(Auth::id())->notifications
			->where('created_at', '<', $created_at)->take(5);

		return view('users.includes.notifications.index', compact('user_notifs'))->render();
	}
}
