<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ChatRoomRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse};

interface ChatRoomRepositoryInterface
{
	public function indexChatroom():array|RedirectResponse|JsonResponse;
	public function fetchWithSelectedUser(int $receiver_id):array|RedirectResponse|JsonResponse;
	public function getChatRooms(int $message_id):array;
	public function sendInvitation(ChatRoomRequest $request):void;
	public function postAcceptInvitationChatroom(ChatRoomRequest $request):null|RedirectResponse;
	public function getAcceptInvitationChatroom(int $chat_room_id):array;
	public function refuseInvitationChatroom(ChatRoomRequest $request):null|JsonResponse;
}
