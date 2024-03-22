<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\View\View;

class NotifsComposer
{
	protected $notifs;
	protected $unread_notifs_count;

	public function __construct()
	{
		if (Auth::check()) {
			$auth_id = Auth::id();

			if (Cache::has('notifs_' . $auth_id)) {
				$notifs = Cache::get('notifs_' . $auth_id);
				$unread_notifs_count      = Cache::get('notifs_count_' . $auth_id);
			} else {
				$auth_user = User::find(Auth::id());

				$notifs              = $auth_user->notifications->take(5);
				$unread_notifs_count = $auth_user->unreadnotifications->count();

				Cache::put('notifs_' . $auth_id, $notifs, now()->addHours(2));
				Cache::put('notifs_count_' . $auth_id, $unread_notifs_count, now()->addHours(2));
			}

			$this->notifs = $notifs;
			$this->unread_notifs_count =$unread_notifs_count;
		}
	}

	/**
	 * Bind data to the view.
	 *
	 * @param  \Illuminate\View\View  $view
	 *
	 * @return void
	 */
	public function compose(View $view)
	{
		$view->with(['user_notifs' => $this->notifs, 'unread_notifs_count' => $this->unread_notifs_count]);
	}
}
