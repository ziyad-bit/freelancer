<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Http\Requests\{ChatRoomRequest};
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Models\User;
use App\Notifications\{AddUserToChatNotification};
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\{Auth, DB};

class ChatRoomRepository implements ChatRoomRepositoryInterface
{
	// MARK: indexChatroom
	public function indexChatroom():array
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3)
		->get();

		$messages     = [];
		$receiver = null;
		$chat_room_id = null;

		if ($all_chat_rooms->count() > 0) {
			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'receiver'   => $receiver,
			'show_chatroom'  => true,
		];
	}

	// MARK: fetch
	public function fetchWithSelectedUser(int $receiver_id): array|RedirectResponse
	{
		$receiver = DB::table('users')->find($receiver_id, ['name', 'image', 'id']);
		if (!$receiver) {
			return to_route('chat-rooms.index')->with('error', 'user not found');
		}
			
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3);

		$selected_chat_room = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]
		);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

		$messages     = [];
		$receiver = null;
		$chat_room_id = null;

		foreach ($all_chat_rooms as $chat_room) {
			if ($chat_room->receiver_id === $receiver_id) {
				$chat_room_id = $chat_room->chat_room_id;

				break;
			}
		}

		if (!$chat_room_id) {
			$chat_room_id = DB::table('chat_rooms')
				->insertGetId(
					[
						'owner_id'    => $auth_id,
						'created_at'  => now(),
					]
				);

			DB::table('messages')
				->insert([
					'chat_room_id' => $chat_room_id, 
					'receiver_id' => $receiver_id,
					'sender_id' => $auth_id,
					'chat_room_id' => $chat_room_id,
					'sender_id' => $auth_id,
					'text' => 'new_chat_room%',
					'created_at'  => now(),
					]);

			DB::table('chat_room_user')
				->insert([
					['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
					['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
				]);
		} else {
			$messages = Messages::index($chat_room_id);
		}

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'receiver'   => $receiver,
			'show_chatroom'  => true,
		];
	}

	//MARK: get chat rooms   
	public function getChatRooms(int $message_id):array
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			$message_id
		)
		->latest('messages.id')
		->limit(3)
		->get();

		$chat_room_id       = null;
		$receiver       = null;
		$messages           = [];
		$show_chatroom      = false;
		$searchName         = null;
		$is_chatroom_page_1 = true;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'is_chatroom_page_1', 'receiver'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'receiver', 'messages'))->render();

		return [
			'chat_rooms_view' => $chat_room_view,
			'chat_box_view'   => $chat_box_view,
		];
	}

	// MARK: acceptInvitation
	public function acceptInvitationForChatroom(int $chat_room_id): array|RedirectResponse
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3);

		$messages = [];
		$receiver = null;

		$chat_room = DB::table('chat_room_user')
				->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id])->first();

		if (!$chat_room) {
			return to_route('chat-rooms.index')->with('error', 'user not found');
		}

		$messages = Messages::index($chat_room_id);

		$selected_chat_room = ChatRooms::fetch(
			['messages.chat_room_id' => $chat_room_id, 'last' => 1],
			[]
		);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'receiver'   => $receiver,
			'show_chatroom'  => true,
		];
	}

	//MARK: sendInvitation  
	public function sendInvitation(int $receiver_id, int $chat_room_id):JsonResponse
	{
		$receiver = User::find($receiver_id);
		$view     = view('users.includes.notifications.send_user_invitation', compact('receiver_id', 'chat_room_id'))->render();
		$user     = Auth::user();

		$receiver->notify(new AddUserToChatNotification($chat_room_id, $user->image, $user->name, $view));

		return response()->json();
	}

	//MARK: addUserToChatRoom   
	public function addUserToChatRoom(ChatRoomRequest $req):RedirectResponse
	{
		$data = $req->validated() + ['created_at' => now()];

		DB::table('chat_room_user')->insert($data);

		return to_route(
			'chat-rooms.acceptInvitation',
			[
				'chat_room_id' => $data['chat_room_id'],
			]
		);
	}
}
