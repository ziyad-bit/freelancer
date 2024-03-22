<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OnlineUser
{
	protected $all_notifications;

	protected $notifs_count;

	public function __construct()
	{
		if (Auth::check()) {
			$auth_user = Auth::user();
			$auth_id   = Auth::id();

			Cache::put('online_user_' . $auth_id, $auth_id, now()->addMinutes(4));

			if ($auth_user->online == 0) {
				$auth_user->update(['online' => 1]);
			}
		}
	}

	public function compose():void
	{
	}
}
