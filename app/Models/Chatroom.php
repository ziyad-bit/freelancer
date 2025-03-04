<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chatroom extends Model 
{
    use HasFactory;

    protected $table = 'chat_rooms';
    const UPDATED_AT = null;
    public $incrementing = false;

    public function chatroom_user()
    {
        return $this->belongsToMany(User::class, 'chat_room_user', 'chat_room_id', 'user_id');
    }
}