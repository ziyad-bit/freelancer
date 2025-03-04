<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat_room_user extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $table = 'chat_room_user';
}
