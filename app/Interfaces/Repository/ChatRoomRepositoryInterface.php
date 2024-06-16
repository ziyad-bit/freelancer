<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ChatRoomRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

interface ChatRoomRepositoryInterface
{
	public function sendInvitation(int $receiver_id, int $chat_room_id):JsonResponse;
	public function addUserToChatRoom(ChatRoomRequest $req):RedirectResponse;
}
