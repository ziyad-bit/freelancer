<?php

namespace App\Http\Controllers\Users;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatRoomRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use App\Interfaces\Repository\ChatRoomRepositoryInterface;

class ChatRoomController extends Controller
{
	public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository)
	{
		$this->middleware(['auth','verifyEmail']);
	}

	// MARK:index
	public function index():View
	{
		$data = $this->chatRoomRepository->indexChatroom();
		
		return view('users.chat.index', $data);
	}

	// MARK:fetch
	public function fetch(int $receiver_id):View|RedirectResponse
	{
		$data = $this->chatRoomRepository->fetchWithSelectedUser($receiver_id);

		return view('users.chat.index',$data);
	}

	//MARK: show_more
	public function show_more_chat_rooms(int $message_id):JsonResponse
	{
		$data = $this->chatRoomRepository->getChatRooms($message_id);

		return response()->json($data);
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

		return response()->json(['success_msg' => 'You sent the invitation successfully']);
	}

	// MARK:AcceptInvitation
	public function post_accept_invitation(ChatRoomRequest $request):View|RedirectResponse
	{
		$this->chatRoomRepository->postAcceptInvitationChatroom($request);

		return to_route('chatrooms.getAcceptInvitation', $request->chat_room_id);
	}

	// MARK:getAcceptInvitation
	public function get_accept_invitation(int $chat_room_id):View|RedirectResponse
	{
		$data = $this->chatRoomRepository->getAcceptInvitationChatroom($chat_room_id);

		return view('users.chat.index', $data);
	}

	// MARK:refuse_invitation
	public function refuse_invitation(ChatRoomRequest $request) : JsonResponse
	{
		$this->chatRoomRepository->refuseInvitationChatroom($request);

		return response()->json();
	}
}
