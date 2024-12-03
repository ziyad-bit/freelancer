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
		$this->middleware('auth');
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
				'show_chatroom'            => $data['show_chatroom'],
				'is_chatroom_page_1'       => true,
			]
		);
	}

	// MARK:fetch
	public function fetch(int $receiver_id):View|RedirectResponse
	{
		$response = $this->chatRoomRepository->fetchWithSelectedUser($receiver_id);

		if (!is_array($response)) {
			return $response;
		}

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'           => $response['all_chat_rooms'],
				'chat_room_id'             => $response['chat_room_id'],
				'messages'                 => $response['messages'],
				'receiver'                 => $response['receiver'],
				'message_id'               => $response['message_id'],
				'show_chatroom'            => $response['show_chatroom'],
				'is_chatroom_page_1'       => true,
			]
		);
	}

	//MARK: show_more
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

	// MARK:send_invitation
	public function get_users() : JsonResponse
	{
		$users = $this->chatRoomRepository->get_chatroom_users();

		return response()->json(['users' => $users]);
	}

	// MARK:send_invitation
	public function send_user_invitation(ChatRoomRequest $request) : JsonResponse
	{
		$response = $this->chatRoomRepository->sendInvitation($request);

		if ($response != null) {
			return $response;
		}

		return response()->json(['success_msg' => 'You sent the invitation successfully']);
	}

	// MARK:AcceptInvitation
	public function post_accept_invitation(ChatRoomRequest $request):View|RedirectResponse
	{
		$response = $this->chatRoomRepository->postAcceptInvitationChatroom($request);

		if ($response != null) {
			return $response;
		}

		return to_route('chatrooms.getAcceptInvitation', $request->chat_room_id);
	}

	// MARK:getAcceptInvitation
	public function get_accept_invitation(int $chat_room_id):View|RedirectResponse
	{
		$data = $this->chatRoomRepository->getAcceptInvitationChatroom($chat_room_id);

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'           => $data['all_chat_rooms'],
				'chat_room_id'             => $data['chat_room_id'],
				'messages'                 => $data['messages'],
				'show_chatroom'            => true,
				'is_chatroom_page_1'       => true,
			]
		);
	}

	// MARK:refuse_invitation
	public function refuse_invitation(ChatRoomRequest $request) : JsonResponse
	{
		$response = $this->chatRoomRepository->refuseInvitationChatroom($request);

		if ($response != null) {
			return $response;
		}

		return response()->json();
	}
}
