<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chatrooms.{chat_room_id}', function ($user,int $chat_room_id) {
    $joined_chat_rooms_ids=DB::table('chat_room_user')
                            ->where('user_id' , $user->id)
                            ->pluck('chat_room_id')
                            ->toArray();

    if (in_array($chat_room_id,$joined_chat_rooms_ids)) {
        return ['name'=>$user->name,'user_id'=>$user->id,'chat_room_id'=>$chat_room_id];
    }else{
        return false;
    }
});

Broadcast::channel('App.Models.User.{user_id}', function ($user,int $user_id) {
    return $user->id === $user_id;
});
