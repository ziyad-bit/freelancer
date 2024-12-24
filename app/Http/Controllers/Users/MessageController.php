<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface};
use Illuminate\Http\{JsonResponse, Request};

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
		$this->middleware('auth');
	}

	//MARK: store
	public function store(MessageRequest $request, FileRepositoryInterface $fileRepository)//:JsonResponse
	{
		$data = $this->messageRepository->storeMessage($request, $fileRepository);

		return response()->json($data);
	}

	//MARK: show
	public function show(int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showMessages($chat_box_id);

		return response()->json(['view' => $view]);
	}

	//MARK:show_old
	public function show_old(Request $request, int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showOldMessages($request, $chat_box_id);

		return response()->json(['view' => $view]);
	}
}
