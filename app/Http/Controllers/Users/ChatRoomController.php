<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Interfaces\Repository\MessageRepositoryInterface;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
use Illuminate\Http\{JsonResponse, RedirectResponse, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatRoomController extends Controller
{
	public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository) 
	{
		
	}

	public function send_user_invitation(int $receiver_id, int $chat_room_id) : JsonResponse
	{
		return $this->chatRoomRepository->sendInvitation($receiver_id , $chat_room_id);
	}

	public function add_user(ChatRoomRequest $req):RedirectResponse
	{
		return $this->chatRoomRepository->addUserToChatRoom($req);
	}
}
