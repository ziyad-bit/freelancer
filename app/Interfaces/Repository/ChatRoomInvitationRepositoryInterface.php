<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;
use App\Http\Requests\ChatRoomRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse};

interface ChatRoomInvitationRepositoryInterface
{
	public function get_chatroom_users(string $chat_room_id):Collection;
	public function sendInvitation(ChatRoomRequest $request):void;
	public function postAcceptInvitationChatroom(ChatRoomRequest $request):void;
	public function getAcceptInvitationChatroom(string $chat_room_id):array;
	public function refuseInvitationChatroom(ChatRoomRequest $request):void;
}
