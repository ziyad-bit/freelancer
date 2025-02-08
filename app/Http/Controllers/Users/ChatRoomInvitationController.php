<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomInvitationRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse};

class ChatRoomInvitationController extends Controller
{
	public function __construct(private ChatRoomInvitationRepositoryInterface $ChatRoomInvitationRepository)
	{
		$this->middleware(['auth', 'verifyEmail']);
		$this->middleware('chatroomInvitation')->only(['post_accept_invitation','refuse_invitation']);
	}

	// MARK:get_users 
	//get all users in all chatrooms to send invitation to one of them
	public function get_users(string $chat_room_id) : JsonResponse
	{
		$users = $this->ChatRoomInvitationRepository->get_chatroom_users($chat_room_id);

		return response()->json(['users' => $users]);
	}

	// MARK:send_invitation
	public function send_user_invitation(ChatRoomRequest $request) : JsonResponse
	{
		$this->ChatRoomInvitationRepository->sendInvitation($request);

		return response()->json(['success_msg' => 'You sent the invitation successfully']);
	}

	// MARK:AcceptInvitation
	public function post_accept_invitation(ChatRoomRequest $request):View|RedirectResponse
	{
		$this->ChatRoomInvitationRepository->postAcceptInvitationChatroom($request);
	
		return to_route('chatrooms.fetch',$request->sender_id);
	}

	// MARK:getAcceptInvitation
	public function get_accept_invitation(string $chat_room_id):View|RedirectResponse
	{
		$data = $this->ChatRoomInvitationRepository->getAcceptInvitationChatroom($chat_room_id);

		return view('users.chat.index', $data);
	}

	// MARK:refuse_invitation
	public function refuse_invitation(ChatRoomRequest $request) : JsonResponse
	{
		$this->ChatRoomInvitationRepository->refuseInvitationChatroom($request);

		return response()->json();
	}
}
