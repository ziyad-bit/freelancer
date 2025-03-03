<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Exceptions\GeneralNotFoundException;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Models\User;
use App\Traits\DatabaseCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB};
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
		$all_chat_rooms = ChatRooms::index(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(4)
		->get();

		$messages     = [];
		$chat_room_id = null;

		if ($all_chat_rooms->count() > 0) {
			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}

		return [
			'messages'                    => $messages,
			'selected_chat_room_id'       => $chat_room_id,
			'all_chat_rooms'              => $all_chat_rooms,
			'show_chatroom'               => true,
			'is_chatroom_page_1'          => true,
		];
	}

	// MARK: fetch
	public function fetchWithSelectedUser(int $receiver_id): array|RedirectResponse
	{
		$messages     = [];
		$auth_id      = Auth::id();

		$receiver = DB::table('users')
			->select(['name', 'image', 'users.id', 'type'])
			->join('user_infos', 'user_id', '=', 'users.id')
			->where('users.id', $receiver_id)
			->first();

		if (!$receiver) {
			throw new GeneralNotFoundException('user');
		}

		//we will get the chat room between the authenticated user and the selected user
		$selected_chat_room_query = ChatRooms::index(
			['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]
		)
		->groupBy('messages.id');

		$selected_chat_room = $selected_chat_room_query->first();

		/**
		 * if the chatroom does not exist, create a new chatroom
		*/
		$created_at = now();
		if (!$selected_chat_room) {
			/**
			 * we will get all the chat rooms with last received message
			 * or last sent message
			 */

			$all_chat_rooms = ChatRooms::index(
				['messages.sender_id' => $auth_id, 'last' => 1],
				['messages.receiver_id' => $auth_id, 'last' => 1],
			)
			->latest('messages.id')
			->limit(4)
			->get();

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

			DB::table('messages')
				->insert([
					'chat_room_id' => $chat_room_id,
					'receiver_id'  => $receiver_id,
					'sender_id'    => $auth_id,
					'text'         => 'new_chat_room%',
					'created_at'   => $created_at,
				]);

			DB::table('chat_room_user')
				->insert([
					['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
					['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
				]);

			$selected_chat_room = $selected_chat_room_query->first();
		} else {
			/**
			 * we will get all the chat rooms with last received message
			 * or last sent message except the selected one
			 */
			$all_chat_rooms = ChatRooms::index(
				['messages.sender_id' => $auth_id, 'last' => 1],
				['messages.receiver_id' => $auth_id, 'last' => 1],
			)
			->latest('messages.id')
			->limit(3)
			->get();

			$messages = Messages::index($selected_chat_room->chat_room_id);
		}

		$all_chat_rooms_count = $all_chat_rooms->count();
		$all_chat_rooms       = $all_chat_rooms
								->union([$all_chat_rooms_count => $selected_chat_room])
								->unique()
								->sortByDesc('id');

		return [
			'messages'              => $messages,
			'all_chat_rooms'        => $all_chat_rooms,
			'selected_chat_room_id' => $selected_chat_room->chat_room_id,
			'show_chatroom'         => true,
			'is_chatroom_page_1'    => true,
		];
	}

	//MARK: get chat rooms
	public function getChatRooms(int $message_id):array
	{
		/**
			get more the chat rooms for infinite scrolling
			with last received message or last sent message
		 */
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::index(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			$message_id,
		)
		->latest('messages.id')
		->limit(4)
		->get();

		$data = [
			'selected_chat_room_id' => null,
			'messages'              => [],
			'show_chatroom'         => false,
			'is_chatroom_page_1'    => true,
			'all_chat_rooms'        => $all_chat_rooms,
		];

		$chat_room_view = view('users.includes.chat.index_chat_rooms', $data)->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', $data)->render();

		return [
			'chat_room_view'  => $chat_room_view,
			'chat_box_view'   => $chat_box_view,
		];
	}
}
