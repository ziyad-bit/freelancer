<?php

namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Messages
{
	public static function index(string $chat_room_id, int $message_id=0 ):Collection
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
			->where('messages.text', '!=', 'new_chat_room%')
			->when(
				$message_id!=0,
				function ($query) use ($message_id) {
					$query->Where('messages.id', '<', $message_id);
				}
			)
			->groupBy('messages.id')
			->orderBy('id', 'desc')
			->limit(6)
			->get();
	}
}
