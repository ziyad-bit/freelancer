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
				'receiver'                 => $data['receiver'],
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
				'receiver'                 => $data_or_redirect['receiver'],
				'message_id'               => $data_or_redirect['message_id'],
				'show_chatroom'            => $data_or_redirect['show_chatroom'],
				'searchName'               => null,
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
		$users=$this->chatRoomRepository->get_chatroom_users();

		return response()->json(['users'=>$users]);
	}

	// MARK:send_invitation
	public function send_user_invitation(ChatRoomRequest $request) : JsonResponse
	{
		return $this->chatRoomRepository->sendInvitation($request);
	}

	// MARK:AcceptInvitation
	public function post_accept_invitation(ChatRoomRequest $request):View|RedirectResponse
	{
		$is_null=$this->chatRoomRepository->postAcceptInvitationChatroom($request);

		if ($is_null != null) {
			return $is_null;
		}

		return to_route('chatrooms.getAcceptInvitation',$request->chat_room_id);
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
				'receiver'                 => null,
				'show_chatroom'            => true,
				'searchName'               => null,
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
