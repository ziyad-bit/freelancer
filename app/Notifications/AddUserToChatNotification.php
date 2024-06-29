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
		public string $image,
		public string $name,
		public string $view,
	) {
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function via()
	{
		return ['database', 'broadcast'];
	}


	public function toBroadcast():BroadcastMessage
	{
		return new BroadcastMessage(
			[
				'view'  => $this->view,
			]
		);
	}

	public function toDatabase():array
	{
		return [
			'chat_room_id' => $this->chat_room_id,
			'sender_image' => $this->image,
			'sender_name'  => $this->name,
		];
	}

	public function databaseType():string
	{
		return 'add_user_to_chat';
	}

	public function viaQueues()
	{
		return [
			'database' => 'database',
		];
	}
}
