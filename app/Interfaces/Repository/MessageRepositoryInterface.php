<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;

interface MessageRepositoryInterface
{
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):array;
	public function showMessages(string $chat_box_id, string $created_at = ''):string;
}
