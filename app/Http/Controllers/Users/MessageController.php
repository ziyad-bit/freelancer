<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class MessageController extends Controller
{
	####################################   index   #####################################
	public function index_chatrooms(int $receiver_id):View
	{
		$all_chat_rooms = DB::table('chat_rooms')
			->select(
				'chat_rooms.*',
				'sender.image   as sender_image',
				'receiver.image as receiver_image',
				'sender.name    as sender_name',
				'receiver.name  as receiver_name'
			)
			->join('users as sender', 'chat_rooms.owner_id', '=', 'sender.id')
			->join('users as receiver', 'chat_rooms.receiver_id', '=', 'receiver.id')
			->where('owner_id', Auth::id())
			->orwhere('receiver_id', Auth::id())
			->simplePaginate(5);

		$chat_room_id = DB::table('chat_rooms')
			->where('owner_id', Auth::id())
			->orwhere('receiver_id', $receiver_id)
			->value('id');

		if (!$chat_room_id) {
			DB::table('chat_rooms')->insert([
				'owner_id'    => Auth::id(),
				'receiver_id' => $receiver_id,
				'created_at'  => now(),
			]);
		} else {
			$messages = DB::table('messages')
				->select(
					'messages.*',
					'sender.image   as sender_image',
					'receiver.image as receiver_image',
					'sender.name    as sender_name',
					'receiver.name  as receiver_name'
				)
				->join('users as sender', 'messages.sender_id', '=', 'sender.id')
				->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
				->where('chat_room_id', $chat_room_id)
				->simplePaginate(10);
		}

		return view('users.chat.index', compact('all_chat_rooms', 'chat_room_id', 'messages'));
	}

	####################################   create   #####################################
	public function create():View
	{
		return view('');
	}

	####################################   store   #####################################
	public function store($request):RedirectResponse
	{
		return to_route('');
	}

	####################################   show   #####################################
	public function show(int $id):View
	{
		return view('');
	}

	####################################   edit   #####################################
	public function edit(int $id):View
	{
		return view('');
	}

	####################################   update   #####################################
	public function update($request, int $id):RedirectResponse
	{
		return to_route('');
	}

	####################################   destroy   #####################################
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
