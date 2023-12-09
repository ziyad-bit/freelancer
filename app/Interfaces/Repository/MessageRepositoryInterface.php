<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\MessageRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};

interface MessageRepositoryInterface
{
	public function getMessages(int $receiver_id):array;
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showMessage(int $id):object|null;
}
