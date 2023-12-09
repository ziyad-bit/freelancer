<?php

namespace App\Repositories;

use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface, SkillRepositoryInterface};
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};

class MessageRepository implements MessageRepositoryInterface
{
	use GetCursor;

	####################################   getMessages   #####################################
	public function getMessages(int $receiver_id):array
	{
		$all_chat_rooms = DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id'
			)
			->where(['messages.sender_id' => Auth::id(), 'last' => 1])
			->orWhere(function ($query) {
				$query->where(['messages.receiver_id' => Auth::id(), 'last' => 1]);
			})
			->get();

		$chat_room_id = DB::table('chat_rooms')
			->where('owner_id', Auth::id())
			->orwhere('receiver_id', $receiver_id)
			->value('id');

		$messages = null;

		if (!$chat_room_id) {
			DB::table('chat_rooms')->insert([
				'owner_id'    => Auth::id(),
				'receiver_id' => $receiver_id,
				'created_at'  => now(),
			]);
		} else {
			$messages = DB::table('messages')
				->join('users as sender', 'messages.sender_id', '=', 'sender.id')
				->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
				->select(
					'messages.*',
					'sender.image   as sender_image',
					'receiver.image as receiver_image',
					'sender.name    as sender_name',
					'receiver.name  as receiver_name'
				)
				->where('chat_room_id', $chat_room_id)
				->limit(10)
				->get();
		}

		return ['messages' => $messages, 'chat_room_id' => $chat_room_id, 'all_chat_rooms' => $all_chat_rooms];
	}

	####################################   storeMessage   #####################################
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
	}

	####################################   showMessage   #####################################
	public function showMessage(int $id):object|null
	{
		return view();
	}
}
