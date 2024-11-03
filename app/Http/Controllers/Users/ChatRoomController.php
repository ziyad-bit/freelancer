<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse};

class ChatRoomController extends Controller
{
	public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository)
	{
	}

	// MARK:index
	public function index():View
	{
		$data = $this->chatRoomRepository->indexChatroom();

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'           => $data['all_chat_rooms'],
				'chat_room_id'             => $data['chat_room_id'],
				'messages'                 => $data['messages'],
				'receiver'             => $data['receiver'],
				'show_chatroom'            => $data['show_chatroom'],
				'searchName'               => null,
				'is_chatroom_page_1'       => true,
			]
		);
	}

	// MARK:fetch
	public function fetch(int $receiver_id):View|RedirectResponse
	{
		$data_or_redirect = $this->chatRoomRepository->fetchWithSelectedUser($receiver_id);

		if (!is_array($data_or_redirect)) {
			return $data_or_redirect;
		}

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'           => $data_or_redirect['all_chat_rooms'],
				'chat_room_id'             => $data_or_redirect['chat_room_id'],
				'messages'                 => $data_or_redirect['messages'],
				'receiver'             => $data_or_redirect['receiver'],
				'show_chatroom'            => $data_or_redirect['show_chatroom'],
				'searchName'               => null,
				'is_chatroom_page_1'       => true,
			]
		);
	}

	//MARK: show_chat_rooms   
	public function show_more_chat_rooms(int $message_id):JsonResponse
	{
		$chat = $this->chatRoomRepository->getChatRooms($message_id);

		return response()->json(
			[
				'chat_room_view' => $chat['chat_rooms_view'],
				'chat_box_view'  => $chat['chat_box_view'],
			]
		);
	}

	// MARK:acceptInvitation
	public function acceptInvitation(int $receiver_id = null, int $chat_room_id = null):View|RedirectResponse
	{
		$data_or_redirect = $this->chatRoomRepository->acceptInvitationForChatroom($receiver_id, $chat_room_id);

		if (!is_array($data_or_redirect)) {
			return $data_or_redirect;
		}

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'           => $data_or_redirect['all_chat_rooms'],
				'chat_room_id'             => $data_or_redirect['chat_room_id'],
				'messages'                 => $data_or_redirect['messages'],
				'receiver'             => $data_or_redirect['receiver'],
				'show_chatroom'            => $data_or_redirect['show_chatroom'],
				'searchName'               => null,
				'is_chatroom_page_1'       => true,
			]
		);
	}

	// MARK:send_invitation
	public function send_user_invitation(int $receiver_id, int $chat_room_id) : JsonResponse
	{
		return $this->chatRoomRepository->sendInvitation($receiver_id, $chat_room_id);
	}

	// MARK:add_user
	public function add_user(ChatRoomRequest $req):RedirectResponse
	{
		return $this->chatRoomRepository->addUserToChatRoom($req);
	}
}
