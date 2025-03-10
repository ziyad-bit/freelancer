<?php

namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Messages
{
	public static function index(string $chat_room_id, string $created_at = ''):Collection
	{
		return DB::table('messages')
			->select(
				'messages.*',
				'sender.image   as sender_image',
				'sender.name    as sender_name',
				DB::raw('GROUP_CONCAT(message_files.file order by message_files.file) as files_name'),
				DB::raw('GROUP_CONCAT(message_files.type order by message_files.file) as files_type'),
			)
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->leftJoin('message_files', 'messages.id', '=', 'message_files.message_id')
			->where('messages.chat_room_id', $chat_room_id)
			->when(
				$created_at != '',
				function ($query) use ($created_at) {
					$query->Where('messages.created_at', '<', $created_at);
				}
			)
			->groupBy('messages.id')
			->latest('messages.created_at')
			->limit(8)
			->get();
	}
}
