<?php

use Illuminate\Support\Facades\Auth;

function get_selected_chat_room(bool $show_chatroom, int $i, string $selected_chat_room_id = null, string $chat_room_id) : bool
{
	$is_selected_chat_room = false;
	if (!isset($selected_chat_room_id)) {
		if ($show_chatroom === true) {
			$is_selected_chat_room = $i == 0;
		}
	} else {
		if ($show_chatroom === true) {
			$is_selected_chat_room = $chat_room_id === $selected_chat_room_id;
		}
	}

	return $is_selected_chat_room;
}

function get_receiver_data(stdClass $message) : array
{
	if ($message->sender_id !== Auth::id()) {
		$receiver_name  = $message->sender_name;
		$receiver_id    = $message->sender_id;
		$receiver_image = $message->sender_image;
	} else {
		$receiver_name  = $message->receiver_name;
		$receiver_id    = $message->receiver_id;
		$receiver_image = $message->receiver_image;
	}

	return [
		'receiver_name'  => $receiver_name,
		'receiver_id'    => $receiver_id,
		'receiver_image' => $receiver_image,
	];
}
