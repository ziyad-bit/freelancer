<?php

namespace App\Repositories;

use App\Classes\ChatRooms;
use App\Http\Requests\SearchRequest;
use App\Interfaces\Repository\SearchRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SearchRepository implements SearchRepositoryInterface
{
	// storeMessage   #####################################
	public function searchChatroom(SearchRequest $request):array
	{
		$searchName     = $request->safe()->search;
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			null,
			$searchName
		)
		->groupBy('messages.id')
		->limit(3)
		->get();

		$chat_room_id       = null;
		$new_receiver       = null;
		$messages           = [];
		$show_chatroom      = false;
		$is_chatroom_page_1 = false;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'is_chatroom_page_1'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'new_receiver', 'messages'))->render();

		return [
			'chat_room_view' => $chat_room_view,
			'chat_box_view'  => $chat_box_view,
		];
	}
}
