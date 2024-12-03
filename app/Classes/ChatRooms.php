<?php

namespace App\Classes;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ChatRooms
{
	// get    #####################################
	public static function fetch(array $last_msg_send, array $last_msg_receive, int $message_id = null, string $searchName = null):Builder
	{
		return DB::table('messages')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id as chatroom_id',
				DB::raw('GROUP_CONCAT(DISTINCT chat_room_user.user_id) as chat_room_users_ids'),
			)
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->join('chat_room_user', 'messages.chat_room_id', '=', 'chat_room_user.chat_room_id')
			->when($searchName != null, function ($query) use ($searchName) {
				$query->where(function ($query) use ($searchName) {
					$query->where('sender.name', 'LIKE', "%{$searchName}%")
						->orWhere('receiver.name', 'LIKE', "%{$searchName}%");
				});
			})
			->when($message_id, fn ($query) => $query->where('messages.id', '<', $message_id))
			->where($last_msg_send)
			->when($last_msg_receive != [], function ($query) use ($last_msg_receive) {
				$query->orwhere(fn ($query) => $query->where($last_msg_receive));
			})
			->groupBy('messages.id');
	}
}
