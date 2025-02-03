<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):array;
	public function showMessages(string $chat_box_id):string;
	public function showOldMessages(string $chat_box_id,int $message_id):string;
}
