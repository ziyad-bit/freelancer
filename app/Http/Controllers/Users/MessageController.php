<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\MessageRepositoryInterface;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
	}

	####################################   index   #####################################
	public function index_chatrooms(int $receiver_id):View
	{
		$messages_chat_room = $this->messageRepository->getMessages($receiver_id);

		return view('users.chat.index', [
			'all_chat_rooms' => $messages_chat_room['all_chat_rooms'],
			'chat_room_id'   => $messages_chat_room['chat_room_id'],
			'messages'       => $messages_chat_room['messages'],
		]);
	}

	####################################   store   #####################################
	public function store(MessageRequest $request):JsonResponse
	{
		$this->messageRepository->storeMessage($request);

		return response()->json();
	}

	####################################   show_old   #####################################
	public function show_old(Request $request, int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showOldMessage($request, $chat_box_id);

		return response()->json(['view' => $view]);
	}
}
