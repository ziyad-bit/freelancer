<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
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
		public string $sender_name,
		public string $sender_image,
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
			'sender_image' => $this->sender_image,
			'sender_name'  => $this->sender_name,
		];
	}

	public function databaseType():string
	{
		return 'invitation_to_chatroom';
	}

	public function viaQueues()
	{
		return [
			'database' => 'database',
		];
	}
}
