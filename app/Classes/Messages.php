<?php

namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Messages
{
	// get    #####################################
	public static function index(int $chat_room_id, $request = null, bool $oldMsgs = false):Collection
	{
		return DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->leftJoin('message_files', 'messages.id', '=', 'message_files.message_id')
			->select(
				'messages.*',
				'sender.image   as sender_image',
				'sender.name    as sender_name',
				DB::raw('GROUP_CONCAT(message_files.file order by message_files.file) as files_name'),
				DB::raw('GROUP_CONCAT(message_files.type order by message_files.file) as files_type'),
			)
			->where('messages.chat_room_id', $chat_room_id)
			->when(
				$oldMsgs,
				function ($query) use ($request) {
					$query->Where('messages.id', '<', $request->first_msg_id);
				}
			)
			->groupBy('messages.id')
			->orderBy('id', 'desc')
			->limit(3)
			->get();
	}
}
