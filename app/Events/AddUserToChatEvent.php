<?php

namespace App\Events;

use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddUserToChatEvent
{
	use Dispatchable, InteractsWithSockets, SerializesModels;


	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(
		public array $data,
		public string $chat_room_id,
		public int $receiver_id,
	) {
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('channel-name');
	}
}
