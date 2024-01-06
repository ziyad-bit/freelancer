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
			->latest()
			->limit(3)
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
				->orderBy('id', 'desc')
				->limit(3)
				->get();
		}

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
		];
	}

	####################################   storeMessage   #####################################
	public function storeMessage(MessageRequest $request):void
	{
		$data = $request->validated() + ['created_at' => now(), 'sender_id' => Auth::id()];

		DB::table('messages')
			->where([
				'sender_id'   => Auth::id(),
				'receiver_id' => $request->receiver_id,
				'last'        => 1,
			])
			->orWhere(function ($query) use ($request) {
				$query->where([
					'receiver_id' => Auth::id(),
					'sender_id'   => $request->sender_id,
					'last'        => 1,
				]);
			})
			->update(['last' => 0]);

		DB::table('messages')->insert($data);
	}

	####################################   showMessage   #####################################
	public function showOldMessage(Request $request, int $chat_box_id):string
	{
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
				->where('messages.id', '<', $request->first_msg_id)
				->where('messages.chat_room_id', $chat_box_id)
				->orderBy('id', 'desc')
				->limit(3)
				->get();

		return $view = view('users.includes.chat.index_msgs', compact('messages'))->render();
	}
}
