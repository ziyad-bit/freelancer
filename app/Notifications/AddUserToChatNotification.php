<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\{BroadcastMessage};
use Illuminate\Notifications\Notification;

class AddUserToChatNotification extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		public string $chat_room_id,
		public string $receiver_id,
		public string $view,
	) {
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 *
	 * @return array
	 */
	public function via()
	{
		return ['database', 'broadcast'];
	}


	public function toBroadcast():BroadcastMessage
	{
		return new BroadcastMessage([
			'view'  => $this->view,
        ]);
	}

	public function toDatabase():array
	{
		return [
			'user_id'      => $this->receiver_id,
			'chat_room_id' => $this->chat_room_id,
		];
	}

	public function databaseType():string
	{
		return 'add_user_to_chat';
	}

	public function viaQueues()
	{
		return [
			'database' => 'addUserNotify',
		];
	}
}
