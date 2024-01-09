<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
	public function getMessages(int $receiver_id):array;
	public function storeMessage(MessageRequest $request):void;
	public function showOldMessages(Request $request, int $chat_box_id):string;
	public function showMessages(int $chat_box_id):string;
}
