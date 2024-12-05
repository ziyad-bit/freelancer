<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):array;
	public function showMessages(int $chat_box_id):string;
	public function showOldMessages(Request $request, int $chat_box_id):string;
}
