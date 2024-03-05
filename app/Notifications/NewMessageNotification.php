<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		public array $data,
		public string $user_name,
		public string $user_image,
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
	public function via($notifiable)
	{
		return ['database', 'broadcast'];
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			'text'         => $this->data['text'],
			'sender_name'  => $this->user_name,
			'sender_image' => $this->user_image,
			'view'         => $this->view,
		];
	}

	public function databaseType():string
	{
		return 'message';
	}

	public function viaQueues():array
	{
		return [
			'broadcast'  => 'messageNotif',
			'database'   => 'messageNotif',
		];
	}
}
