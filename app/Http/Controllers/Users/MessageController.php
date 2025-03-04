<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface};
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
		$this->middleware(['auth', 'verifyEmail']);
	}

	//MARK: store
	public function store(MessageRequest $request, FileRepositoryInterface $fileRepository)//:JsonResponse
	{
		$data = $this->messageRepository->storeMessage($request, $fileRepository);

		return response()->json($data);
	}

	//MARK: show
	public function show(string $chat_box_id,string $created_at = ''):JsonResponse
	{
		$view = $this->messageRepository->showMessages($chat_box_id,$created_at);

		return response()->json(['view' => $view]);
	}
}
