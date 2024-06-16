<?php

namespace App\Events;

use Illuminate\Broadcasting\{InteractsWithSockets, PresenceChannel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $queue = 'message';

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(public array $data)
	{
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('chat-room.' . $this->data['chat_room_id']);
	}
}
