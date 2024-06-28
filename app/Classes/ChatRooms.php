<?php

namespace App\Classes;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ChatRooms
{
	####################################     get    #####################################
	public static function index(array $last_sender_msg ,array $last_receiver_msg):Builder
	{
		return DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->join('chat_room_user', 'messages.chat_room_id', '=', 'chat_room_user.chat_room_id')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id as chatroom_id',
				DB::raw('GROUP_CONCAT(DISTINCT chat_room_user.user_id) as chat_room_users_ids'),
			)
			->where($last_sender_msg)
			->when($last_receiver_msg !== [], function ($query)  use ($last_receiver_msg){
				$query->orWhere(function ($query) use ($last_receiver_msg) {
					$query->where($last_receiver_msg);
				});
			})
			->groupBy('messages.id');
			
	}
}
