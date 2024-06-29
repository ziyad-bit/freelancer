<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\MessageRepositoryInterface;
use Illuminate\Http\{JsonResponse, Request};

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
		$this->middleware('auth');
	}

	// index   #####################################
	public function index_chatrooms(int $receiver_id = null, int $chat_room_id = null)
	{
		$data = $this->messageRepository->getMessages($receiver_id, $chat_room_id);

		if (!is_array($data)) {
			return $data;
		}

		return view(
			'users.chat.index',
			[
				'all_chat_rooms'      => $data['all_chat_rooms'],
				'chat_room_id'        => $data['chat_room_id'],
				'messages'            => $data['messages'],
				'new_receiver'        => $data['new_receiver'],
			]
		);
	}

	// store   #####################################
	public function store(MessageRequest $request):JsonResponse
	{
		$this->messageRepository->storeMessage($request);

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
