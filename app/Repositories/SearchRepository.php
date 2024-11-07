<?php

namespace App\Repositories;

use App\Classes\Projects;
use App\Classes\ChatRooms;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SearchRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\{Auth};
use Illuminate\Pagination\CursorPaginator;
use App\Interfaces\Repository\SearchRepositoryInterface;
use Illuminate\Contracts\View\View;

class SearchRepository implements SearchRepositoryInterface
{
	//MARK: searchChatroom
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
		->limit(3)
		->get();

		$chat_room_id       = null;
		$receiver           = null;
		$messages           = [];
		$show_chatroom      = false;
		$is_chatroom_page_1 = false;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'receiver','is_chatroom_page_1'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'searchName', 'receiver', 'messages'))->render();

		return [
			'chat_room_view' => $chat_room_view,
			'chat_box_view'  => $chat_box_view,
		];
	}

	public function show_recent_projects():string
	{
		$searches = DB::table('searches')
					->select('search')
					->where('user_id',Auth::id())
					->latest()
					->limit(5)
					->get();

		return view('users.includes.search.index_recent',compact('searches'))->render();
	}

	public function show_projects(SearchRequest $request):string
	{
		$searchTitle = $request->search;
		$projects = DB::table('projects')
					->select('title')
					->where('title', 'LIKE', "%{$searchTitle}%")
					->limit(5)
					->get();

		return view('users.includes.search.index',compact('projects'))->render();
	}
}
