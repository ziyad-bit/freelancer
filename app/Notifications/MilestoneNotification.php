<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class MilestoneNotification extends Notification implements ShouldQueue
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
				'view'         => $this->view,
			]
		);
	}

	public function toDatabase():array
	{
		return [
			'amount'       => $this->data['amount'],
			'sender_name'  => $this->user_name,
			'sender_image' => $this->user_image,
		];
	}

	public function databaseType():string
	{
		return 'milestone';
	}

	public function viaQueues():array
	{
		return [
			'database'   => 'database',
		];
	}
}
