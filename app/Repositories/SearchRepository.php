<?php

namespace App\Repositories;

use App\Classes\ChatRooms;
use App\Http\Requests\SearchRequest;
use App\Interfaces\Repository\SearchRepositoryInterface;
use Illuminate\Support\Facades\{Auth, DB};

class SearchRepository implements SearchRepositoryInterface
{
	//MARK: searchChatroom
	public function searchChatroom(SearchRequest $request):array
	{
		/**
		 * we will get the chat rooms with last received message
		 * or last sent message
		 * and we will search the chat rooms by the name of the user
		 */
		$searchName     = $request->safe()->search;
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::index(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			0,
			'',
			$searchName
		)
		->limit(3)
		->get();

		$chat_room_id       = null;
		$messages           = [];
		$show_chatroom      = false;
		$is_chatroom_page_1 = false;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'is_chatroom_page_1'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'messages'))->render();

		return [
			'chat_room_view' => $chat_room_view,
			'chat_box_view'  => $chat_box_view,
		];
	}

	//MARK: RecentProjects
	//show recent searches under the search bar after focusing on it
	public function show_recent_projects():string
	{
		$searches = DB::table('searches')
					->select('search')
					->where('user_id', Auth::id())
					->latest()
					->limit(5)
					->get();

		return view('users.includes.search.index_recent', compact('searches'))->render();
	}

	//MARK: projectsAfterTyping
	/*
		show searches about skills after typing in the search bar
	**/
	public function show_skills_after_typing(SearchRequest $request):string
	{
		$search    = $request->search;
		$skills    = DB::table('skills')
					->select('skill')
					->where('skill', 'LIKE', "{$search}%")
					->limit(5)
					->get();

		return view('users.includes.search.index', compact('skills'))->render();
	}
}
