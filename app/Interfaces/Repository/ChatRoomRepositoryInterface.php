<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ChatRoomRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse};

interface ChatRoomRepositoryInterface
{
	public function indexChatroom():array|RedirectResponse|JsonResponse;
	public function fetchWithSelectedUser(int $receiver_id):array|RedirectResponse|JsonResponse;
	public function getChatRooms(int $message_id):array;
	public function sendInvitation(ChatRoomRequest $request):JsonResponse;
	public function acceptInvitationForChatroom(int $chat_room_id):array|RedirectResponse|JsonResponse;
	public function refuseInvitationForChatroom(ChatRoomRequest $request):null|JsonResponse;
}
