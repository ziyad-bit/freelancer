<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Exceptions\{GeneralNotFoundException, RecordExistException};
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
use App\Traits\DatabaseCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Str;

class ChatRoomRepository implements ChatRoomRepositoryInterface
{
	use DatabaseCache;

	// MARK: indexChatroom
	public function indexChatroom():array
	{
		/**
		 * we will get the chat rooms with last received message 
		 * or last sent message
		 */
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3)
		->get();

		$messages     = [];
		$chat_room_id = null;

		if ($all_chat_rooms->count() > 0) {
			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}

		return [
			'messages'           => $messages,
			'chat_room_id'       => $chat_room_id,
			'all_chat_rooms'     => $all_chat_rooms,
			'show_chatroom'      => true,
			'is_chatroom_page_1' => true,
		];
	}

	// MARK: fetch
	public function fetchWithSelectedUser(int $receiver_id): array|RedirectResponse
	{
		$messages     = [];
		$chat_room_id = null;
		$message_id  = 0;

		$receiver = DB::table('users')->find($receiver_id, ['name', 'image', 'id']);
		if (!$receiver) {
			throw new GeneralNotFoundException('user not found');
		}

		/**
		 * we will get the chat rooms with last received message 
		 * or last sent message
		 */
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(4);

		//we will get the chat room between the authenticated user and the selected user
		$selected_chat_room = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]
		);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

		foreach ($all_chat_rooms as $chat_room) {
			if ($chat_room->receiver_id === $receiver_id) {
				$chat_room_id = $chat_room->chat_room_id;

				break;
			}
		}

		$created_at = now();

		/**
		 * if the chat room does not exist, create a new chat room
		 */
		if (!$chat_room_id) {
			DB::table('chat_rooms')
				->insert(
					[
						'id'          => Str::uuid(),
						'owner_id'    => $auth_id,
						'created_at'  => $created_at,
					]
				);

			$chat_room_id = DB::table('chat_rooms')
				->where(['created_at' => $created_at, 'owner_id' => $auth_id])
				->value('id');

			$message_id = DB::table('messages')
				->insertGetId([
					'chat_room_id' => $chat_room_id,
					'receiver_id'  => $receiver_id,
					'sender_id'    => $auth_id,
					'text'         => 'new_chat_room%',
					'created_at'   => now(),
				]);

			DB::table('chat_room_user')
				->insert([
					['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
					['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
				]);
		} else {
			$messages = Messages::index($chat_room_id);
			$receiver = null;
		}

		return [
			'messages'           => $messages,
			'chat_room_id'       => $chat_room_id,
			'all_chat_rooms'     => $all_chat_rooms,
			'receiver'           => $receiver,
			'message_id'         => $message_id,
			'show_chatroom'      => true,
			'is_chatroom_page_1' => true,
		];
	}

	//MARK: get chat rooms
	public function getChatRooms(int $message_id):array
	{
		/**
			get more the chat rooms with last received message or last sent message
		 */
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			$message_id
		)
		->latest('messages.id')
		->limit(4)
		->get();

		$chat_room_id       = null;
		$messages           = [];
		$show_chatroom      = false;
		$is_chatroom_page_1 = true;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'is_chatroom_page_1'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'messages'))->render();

		return [
			'chat_room_view' => $chat_room_view,
			'chat_box_view'   => $chat_box_view,
		];
	}
}
