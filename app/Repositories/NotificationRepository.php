<?php

namespace App\Repositories;


use App\Interfaces\Repository\NotificationRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
	public function update(): void
	{
		User::find(Auth::id())->unreadNotifications->markAsRead();
	}

	public function show_old_notifs(string $created_at): string
	{
		$user_notifs = User::find(Auth::id())->notifications
			->where('created_at', '<', $created_at)->take(5);

		return view('users.includes.notifications.index', compact('user_notifs'))->render();
	}
}
