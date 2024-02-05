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

Broadcast::channel('chat-room.{chat_room_id}', function ($user,int $chat_room_id) {
    $joined_chat_rooms_ids=DB::table('chat_rooms')
                            ->where('owner_id' , $user->id)
                            ->orWhere('receiver_id' , $user->id)
                            ->pluck('id')
                            ->toArray();

    if (in_array($chat_room_id,$joined_chat_rooms_ids)) {
        return ['name'=>$user->name,'user_id'=>$user->id];
    }else{
        return false;
    }
});
