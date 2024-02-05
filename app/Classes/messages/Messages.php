<?php

namespace App\Classes\Messages;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Messages
{
	####################################     get    #####################################
	public static function get(int $chat_box_id):Builder
	{
		return DB::table('messages')
				->join('users as sender', 'messages.sender_id', '=', 'sender.id')
				->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
				->select(
					'messages.*',
					'sender.image   as sender_image',
					'receiver.image as receiver_image',
					'sender.name    as sender_name',
					'receiver.name  as receiver_name'
				)
				->where('messages.chat_room_id', $chat_box_id);
	}
}
