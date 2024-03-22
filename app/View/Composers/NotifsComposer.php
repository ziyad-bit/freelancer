<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\View\View;

class NotifsComposer
{
	private $notifs;
	private $unread_notifs_count;
	private $auth_user;

	public function __construct()
	{
		if (Auth::check()) {
			$auth_id         = Auth::id();
			$this->auth_user = User::find($auth_id);
			
			$notifs = Cache::remember('notifs_' . $auth_id, now()->addHours(2), function (){
				return $this->auth_user->notifications->take(5);
			});

			$unread_notifs_count = Cache::remember('notifs_count_' . $auth_id, now()->addHours(2), function(){
				return $this->auth_user->unreadnotifications->count();
			});

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
