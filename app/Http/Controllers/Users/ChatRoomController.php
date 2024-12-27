<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse};

class ChatRoomController extends Controller
{
	public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository)
	{
		$this->middleware(['auth', 'verifyEmail']);
	}

	// MARK:index
	public function index():View
	{
		$data = $this->chatRoomRepository->indexChatroom();

		return view('users.chat.index', $data);
	}

	// MARK:fetch
	public function fetch(int $receiver_id):View|RedirectResponse
	{
		$data = $this->chatRoomRepository->fetchWithSelectedUser($receiver_id);

		return view('users.chat.index', $data);
	}

	//MARK: show_more
	public function show_more_chat_rooms(int $message_id):JsonResponse
	{
		$data = $this->chatRoomRepository->getChatRooms($message_id);

		return response()->json($data);
	}
}
