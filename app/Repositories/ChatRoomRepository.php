<?php

namespace App\Repositories;

use App\Classes\Messages\Messages;
use App\Events\MessageEvent;
use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
use App\Notifications\NewMessageNotification;
use App\Traits\GetCursor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB, Notification};

class ChatRoomRepository implements ChatRoomRepositoryInterface
{
	use GetCursor;

	####################################   getMessages   #####################################
	public function sendInvitation(int $receiver_id, int $chat_room_id):JsonResponse
	{
		$receiver = User::find($receiver_id);
		$view     = view('users.includes.notifications.send_user_invitation', compact('receiver_id','chat_room_id'))->render();
		$user     = Auth::user();
        
		$receiver->notify(new AddUserToChatNotification($chat_room_id,$user->image, $user->name, $view));

		return response()->json();
	}

	####################################   storeMessage   #####################################
	public function addUserToChatRoom(ChatRoomRequest $req):RedirectResponse
	{
		$data = $req->validated() + ['created_at' => now()];

		DB::table('chat_room_user')->insert($data);

		return to_route('chat-rooms.index',[
			'receiver_id'=>0,
			'chat_room_id'=>$data['chat_room_id']
		]);
	}
}
