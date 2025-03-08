<?php

namespace App\Listeners;

use App\Events\AddUserToChatEvent;
use App\Exceptions\RecordExistException;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
use Illuminate\Support\Facades\{Auth, DB};

class AddUserToChatListener
{
	/**
	 * Create the event listener.
	 */
	public function __construct()
	{
	}

	/**
	 * Handle the event.
	 */
	public function handle(AddUserToChatEvent $event): void
	{
		$user_in_chatroom = DB::table('chat_room_user')
			->where(['user_id' => $event->receiver_id, 'chat_room_id' => $event->chat_room_id]);

		if ($user_in_chatroom->exists()) {
			throw new RecordExistException('user');
		} else {
			DB::table('chat_room_user')->insert($event->data);
		}

		$user     = Auth::user();
		$receiver = User::find($event->receiver_id);
		$view     = view('users.includes.notifications.send_user_invitation')
					->with('chat_room_id', $event->chat_room_id)
					->render();

		$receiver->notify(
			new AddUserToChatNotification(
				$event->chat_room_id,
				$user->name,
				$user->image,
				$view
			)
		);
	}
}
