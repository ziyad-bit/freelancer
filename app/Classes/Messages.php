<?php

namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Messages
{
	// get    #####################################
	public static function index(int $chat_box_id, $request = null, bool $oldMsgs = false):Collection
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
			->where('messages.chat_room_id', $chat_box_id)
			->when(
				$oldMsgs,
				function ($query) use ($request) {
					$query->orWhere(
						function ($query) use ($request) {
							$query->where('messages.id', '<', $request->first_msg_id);
						}
					);
				}
			)
			->orderBy('id', 'desc')
			->limit(3)
			->get();
		;
	}
}
