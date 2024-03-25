<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    public function add_user(int $receiver_id,int $chat_room_id) : JsonResponse 
    {
        $receiver = User::find($receiver_id);
        $view =view('users.includes.notifications.add_user',compact('receiver_id'))->render();

        $receiver->notify(new AddUserToChatNotification($chat_room_id,$receiver_id,$view));

        return response()->json();
    }
}
