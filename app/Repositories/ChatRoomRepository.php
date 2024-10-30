<?php

namespace App\Repositories;

use App\Classes\ChatRooms;
use App\Classes\Messages;
use App\Http\Requests\{ChatRoomRequest};
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Models\User;
use App\Notifications\{AddUserToChatNotification};
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\{Auth, DB};

class ChatRoomRepository implements ChatRoomRepositoryInterface
{
	use GetCursor;

	// MARK: index
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
		$new_receiver = null;
		$chat_room_id = null;

		if ($all_chat_rooms->count() > 0) {
			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'new_receiver'   => $new_receiver,
			'show_chatroom'  => true,
		];
	}

	public function fetchWithSelectedUser(int $receiver_id): array|RedirectResponse
	{
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
		$chat_room_id = null;
		$new_receiver = null;

		foreach ($all_chat_rooms as $chat_room) {
			if ($chat_room->receiver_id === $receiver_id) {
				$chat_room_id = $chat_room->chat_room_id;

				break;
			}
		}

		if (!$chat_room_id) {
			$chat_room_id = DB::table('chat_rooms')->insertGetId(
				[
					'owner_id'    => $auth_id,
					'created_at'  => now(),
				]
			);

			$new_receiver = DB::table('users')->find($receiver_id, ['name', 'image', 'id']);
			if (!$new_receiver) {
				return to_route('chat-rooms.index')->with('error', 'user not found');
			}

			DB::table('chat_room_user')
				->insert(
					[
						['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
						['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
					]
				);
		} else {
			$messages = Messages::index($chat_room_id);
		}

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'new_receiver'   => $new_receiver,
			'show_chatroom'  => true,
		];
	}

	public function acceptInvitationForChatroom(int $chat_room_id): array|RedirectResponse
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3);

		$messages     = [];
		$new_receiver = null;

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
			'new_receiver'   => $new_receiver,
			'show_chatroom'  => true,
		];
	}
	// getMessages   #####################################
	public function sendInvitation(int $receiver_id, int $chat_room_id):JsonResponse
	{
		$receiver = User::find($receiver_id);
		$view     = view('users.includes.notifications.send_user_invitation', compact('receiver_id', 'chat_room_id'))->render();
		$user     = Auth::user();

		$receiver->notify(new AddUserToChatNotification($chat_room_id, $user->image, $user->name, $view));

		return response()->json();
	}

	// storeMessage   #####################################
	public function addUserToChatRoom(ChatRoomRequest $req):RedirectResponse
	{
		$data = $req->validated() + ['created_at' => now()];

		DB::table('chat_room_user')->insert($data);

		return to_route(
			'chat-rooms.index',
			[
				'receiver_id'  => 0,
				'chat_room_id' => $data['chat_room_id'],
			]
		);
	}
}
