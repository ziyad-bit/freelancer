<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};

interface MessageRepositoryInterface
{
	public function getMessages(int $receiver_id):array;
	public function storeMessage(MessageRequest $request):void;
	public function showOldMessage(Request $request, int $chat_box_id):string;
}
