<?php

namespace App\Classes;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\{Auth, DB};

class ChatRooms
{
	public static function index(int $message_id = 0, string $searchName = '', int $receiver_id = 0):Builder
	{
		/**
		in case searchName is not empty string, we will search for the sender name
		or receiver name

		in case message_id is not 0, we will get more the chatrooms
		that have an message id less than the message_id for infinite scrolling

		in case receiver_id is not 0,we will get chatroom between auth user and
		receiver
		 */
		return DB::table('messages')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
			)
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_room_user as cru1', 'messages.chat_room_id', '=', 'cru1.chat_room_id')
			->when($receiver_id != 0, function ($query) {
				$query->join('chat_room_user as cru2', 'cru1.chat_room_id', '=', 'cru2.chat_room_id');
			})
			->whereIn('messages.chat_room_id', function ($query) use ($receiver_id) {
				$query->from('chat_room_user')
					->select('chat_room_id')
					->where('cru1.user_id', Auth::id())
					->when($receiver_id != 0, function ($query) use ($receiver_id) {
						$query->where('cru2.user_id', $receiver_id);
					});
			})
			->where('last', 1)
			->when($searchName != '', function ($query) use ($searchName) {
				$query->where(function ($query) use ($searchName) {
					$query->where('sender.name', 'LIKE', "{$searchName}%")
						->orWhere('receiver.name', 'LIKE', "{$searchName}%");
				});
			})
			->when($message_id != 0, fn ($query) => $query->where('messages.id', '<', $message_id))
			->distinct()
			->latest('messages.id');
	}
}
