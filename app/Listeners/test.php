<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;

class test
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Handle the event.
	 *
	 * @param object $event
	 *
	 * @return void
	 */
	public function handle(BroadcastNotificationCreated $e)
	{
		User::whereK('id', 1)->update('name', 'ziyad');
	}
}
