<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface};
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
		$this->middleware('auth');
	}

	// store   #####################################
	public function store(MessageRequest $request, FileRepositoryInterface $fileRepository)//:JsonResponse
	{
		DB::enableQueryLog();
		$this->messageRepository->storeMessage($request, $fileRepository);


		return response()->json();
	}

	// show    #####################################
	public function show(int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showMessages($chat_box_id);

		return response()->json(['view' => $view]);
	}

	// show_chat_rooms    #####################################
	public function show_chat_rooms(int $message_id):JsonResponse
	{
		$chat = $this->messageRepository->getChatRooms($message_id);

		return response()->json(
			[
				'chat_room_view' => $chat['chat_rooms_view'],
				'chat_box_view'  => $chat['chat_box_view'],
			]
		);
	}


	// show_old   #####################################
	public function show_old(Request $request, int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showOldMessages($request, $chat_box_id);

		return response()->json(['view' => $view]);
	}
}
