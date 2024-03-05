<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\MessageRepositoryInterface;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class MessageController extends Controller
{
	public function __construct(private MessageRepositoryInterface $messageRepository)
	{
		$this->middleware('auth');
	}

	####################################   index   #####################################
	public function index_chatrooms(int $receiver_id = null):View
	{
		$data = $this->messageRepository->getMessages($receiver_id);

		return view('users.chat.index', [
			'all_chat_rooms'      => $data['all_chat_rooms'],
			'chat_room_id'        => $data['chat_room_id'],
			'messages'            => $data['messages'],
			'user_notifs'         => $data['user_notifs'],
			'unread_notifs_count' => $data['unread_notifs_count'],
		]);
	}

	####################################   store   #####################################
	public function store(MessageRequest $request):JsonResponse
	{
		$this->messageRepository->storeMessage($request);

		return response()->json();
	}

	####################################    show    #####################################
	public function show(int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showMessages($chat_box_id);

		return response()->json(['view' => $view]);
	}

	####################################    show_chat_rooms    #####################################
	public function show_chat_rooms(int $message_id):JsonResponse
	{
		$all_chat_rooms = DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id as chat_room_id'
			)
			->where(function ($query) use ($message_id) {
				$query->where(['messages.sender_id' => Auth::id(), 'last' => 1])
					->where('messages.id', '<', $message_id);
			})
			->orWhere(function ($query) use ($message_id) {
				$query->where(['messages.receiver_id' => Auth::id(), 'last' => 1])
					->where('messages.id', '<', $message_id);
			})
			->latest('messages.id')
			->limit(3)
			->get();

		$chat_room_id = null;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('all_chat_rooms', 'chat_room_id'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxs', compact('all_chat_rooms', 'chat_room_id'))->render();

		return response()->json([
			'chat_room_view' => $chat_room_view,
			'chat_box_view'  => $chat_box_view,
		]);
	}


	####################################   show_old   #####################################
	public function show_old(Request $request, int $chat_box_id):JsonResponse
	{
		$view = $this->messageRepository->showOldMessages($request, $chat_box_id);

		return response()->json(['view' => $view]);
	}
}
