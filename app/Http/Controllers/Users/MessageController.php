<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class MessageController extends Controller
{
	####################################   index   #####################################
	public function index():View
	{
		$messages = DB::table('messages')
			->select('messages.*', 'sender.name as sender_name', 'receiver.name as receiver_name')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->where('messages.sender_id', Auth::id())
			->orwhere('messages.receiver_id', Auth::id())
			->get();

		return view('');
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
