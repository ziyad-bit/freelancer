<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};

interface MessageRepositoryInterface
{
	public function indexMessages():array|RedirectResponse|JsonResponse;
	public function fetchMessages(int $receiver_id):array|RedirectResponse|JsonResponse;
	public function getMessages(int $chat_room_id):array|RedirectResponse|JsonResponse;
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):void;
	public function showMessages(int $chat_box_id):string;
	public function getChatRooms(int $message_id):array;
	public function showOldMessages(Request $request, int $chat_box_id):string;
}
