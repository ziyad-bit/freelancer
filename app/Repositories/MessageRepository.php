<?php

namespace App\Repositories;

use App\Classes\Messages\Messages;
use App\Events\MessageEvent;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\MessageRepositoryInterface;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Traits\GetCursor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB, Notification};

class MessageRepository implements MessageRepositoryInterface
{
	use GetCursor;

	####################################   getMessages   #####################################
	public function getMessages(int $receiver_id = null):array
	{
		$auth_id = Auth::id();
		$all_chat_rooms = DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id as chatroom_id'
			)
			->where(['messages.sender_id' => $auth_id, 'last' => 1])
			->orWhere(function ($query) use($auth_id){
				$query->where(['messages.receiver_id' => $auth_id, 'last' => 1]);
			})
			->latest('messages.id')
			->limit(3);

		$chat_room_id = null;
		if ($receiver_id) {
			$selected_chat_room = DB::table('messages')
				->join('users as sender', 'messages.sender_id', '=', 'sender.id')
				->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
				->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
				->select(
					'messages.*',
					'sender.name as sender_name',
					'sender.image as sender_image',
					'receiver.name as receiver_name',
					'receiver.image as receiver_image',
					'chat_rooms.id as chat_room_id'
				)
				->where(['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1])
				->orWhere(function ($query) use ($receiver_id,$auth_id) {
					$query->where(['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]);
				});

			$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

			foreach ($all_chat_rooms as  $chat_room) {
				if ($chat_room->receiver_id === $receiver_id) {
					$chat_room_id = $chat_room->chat_room_id;

					break;
				}
			}
		} else {
			$all_chat_rooms = $all_chat_rooms->get();
		}

		$messages = null;

		if (!$chat_room_id && $receiver_id) {
			$chat_room_id = DB::table('chat_rooms')->insertGetId([
				'owner_id'    => $auth_id,
				'created_at'  => now(),
			]);
		} elseif ($chat_room_id) {
			$messages = Messages::get($chat_room_id)
				->orderBy('id', 'desc')
				->limit(3)
				->get();
		} else {
			$messages = Messages::get($all_chat_rooms[0]->chat_room_id)
				->orderBy('id', 'desc')
				->limit(3)
				->get();
		}

		return [
			'messages'              => $messages,
			'chat_room_id'          => $chat_room_id,
			'all_chat_rooms'        => $all_chat_rooms,
		];
	}

	####################################   storeMessage   #####################################
	public function storeMessage(MessageRequest $request):void
	{
		$auth_user = Auth::user();
		$data      = $request->validated() + ['created_at' => now(), 'sender_id' => $auth_user->id];
		$receiver_id = $request->receiver_id;

		DB::table('messages')
			->where([
				'sender_id'   => $auth_user->id,
				'receiver_id' => $receiver_id,
				'last'        => 1,
			])
			->orWhere(function ($query) use ($receiver_id, $auth_user) {
				$query->where([
					'receiver_id' => $auth_user->id,
					'sender_id'   => $receiver_id,
					'last'        => 1,
				]);
			})
			->update(['last' => 0]);

		DB::table('messages')->insert($data);

		broadcast(new MessageEvent($data))->toOthers();

		$notif_view = view('users.includes.notifications.send', compact('data'))->render();
		$user       = User::find($request->receiver_id);

		Notification::send($user, new NewMessageNotification($data, $auth_user->name, $auth_user->image, $notif_view));

		if (Cache::has('notifs_' . $receiver_id)) {
			Cache::forget('notifs_' . $receiver_id);
			Cache::increment('notifs_count_' . $receiver_id);
		}
	}

	####################################   showMessage   #####################################
	public function showMessages(int $chat_room_id):string
	{
		$messages = Messages::get($chat_room_id)
				->orderBy('messages.id', 'desc')
				->limit(3)
				->get();

		return $view = view('users.includes.chat.index_msgs', compact('messages'))->render();
	}

	####################################   showMessage   #####################################
	public function showOldMessages(Request $request, int $chat_room_id):string
	{
		$messages = Messages::get($chat_room_id)
				->where('messages.id', '<', $request->first_msg_id)
				->orderBy('id', 'desc')
				->limit(3)
				->get();

		return $view = view('users.includes.chat.index_msgs', compact('messages'))->render();
	}

	####################################   get chat rooms   #####################################
	public function getChatRooms(int $message_id):array
	{
		$auth_id =Auth::id();
		$all_chat_rooms = DB::table('messages')
			->join('users as sender', 'messages.sender_id', '=', 'sender.id')
			->join('users as receiver', 'messages.receiver_id', '=', 'receiver.id')
			->join('chat_rooms', 'messages.chat_room_id', '=', 'chat_rooms.id')
			->select(
				'messages.*',
				'sender.name as sender_name',
				'sender.image as sender_image',
				'receiver.name as receiver_name',
				'receiver.image as receiver_image',
				'chat_rooms.id as chat_room_id'
			)
			->where(function ($query) use ($message_id,$auth_id) {
				$query->where(['messages.sender_id' => $auth_id, 'last' => 1])
					->where('messages.id', '<', $message_id);
			})
			->orWhere(function ($query) use ($message_id,$auth_id) {
				$query->where(['messages.receiver_id' => $auth_id, 'last' => 1])
					->where('messages.id', '<', $message_id);
			})
			->latest('messages.id')
			->limit(3)
			->get();

		$chat_room_id = null;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('all_chat_rooms', 'chat_room_id'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxs', compact('all_chat_rooms', 'chat_room_id'))->render();

		return [
			'chat_rooms_view'=>$chat_room_view ,
			'chat_box_view'=>$chat_box_view ,
		];
	}
}
