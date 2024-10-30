<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};

interface MessageRepositoryInterface
{
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):void;
	public function showMessages(int $chat_box_id):string;
	public function getChatRooms(int $message_id):array;
	public function showOldMessages(Request $request, int $chat_box_id):string;
}
