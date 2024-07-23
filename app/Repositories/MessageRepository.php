<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Events\MessageEvent;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\MessageRepositoryInterface;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Traits\GetCursor;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Cache, DB, Notification};

class MessageRepository implements MessageRepositoryInterface
{
	use GetCursor;

	// MARK: getMessages
	public function getMessages(int $receiver_id = null, int $chat_room_id = null):array|RedirectResponse|JsonResponse 
	{
		if ($receiver_id > 0 && $chat_room_id !== null) {
			return to_route('chat-rooms.index')->with('error', 'something went wrong');
		}

		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::index(
				['messages.sender_id' => $auth_id, 'last' => 1],
				['messages.receiver_id' => $auth_id, 'last' => 1]
			)
			->latest('messages.id')
			->limit(3);

		if ($receiver_id > 0) {
			$selected_chat_room = ChatRooms::index(
				['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1],
				['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]
			);

			$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

			foreach ($all_chat_rooms as  $chat_room) {
				if ($chat_room->receiver_id === $receiver_id) {
					$chat_room_id = $chat_room->chat_room_id;

					break;
				}
			}
		}

		$messages     = [];
		$new_receiver = null;

		if ($chat_room_id === null && $receiver_id > 0) {
			$chat_room_id = DB::table('chat_rooms')->insertGetId(
				[
					'owner_id'    => $auth_id,
					'created_at'  => now(),
				]
			);

			$new_receiver = DB::table('users')->find($receiver_id, ['name', 'image', 'id']);
			if (!$new_receiver) {
				return to_route('chat-rooms.index')->with('error', 'user not found');
			}

			DB::table('chat_room_user')->insert(
				[
					['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
					['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
				]
			);
		} elseif ($chat_room_id !== null && $receiver_id === 0) {
			$chat_room = DB::table('chat_room_user')
				->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id])->first();

			if (!$chat_room) {
				DB::table('chat_room_user')
				->insert(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id]);
			}

			$messages = Messages::index($chat_room_id);

			$selected_chat_room = ChatRooms::index(
				['messages.chat_room_id' => $chat_room_id, 'last' => 1],
				[]
			);

			$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();
		} else {
			if ($receiver_id === null) {
				$all_chat_rooms = $all_chat_rooms->get();
			}

			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}
		
		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
			'new_receiver'   => $new_receiver,
		];
	}

	// storeMessage   #####################################
	public function storeMessage(MessageRequest $request):void
	{
		$auth_user   = Auth::user();
		$data        = $request->validated() + ['created_at' => now(), 'sender_id' => $auth_user->id];
		$receiver_id = $request->receiver_id;

		DB::table('messages')
			->where(
				[
					'sender_id'   => $auth_user->id,
					'receiver_id' => $receiver_id,
					'last'        => 1,
				]
			)
			->orWhere(
				function ($query) use ($receiver_id, $auth_user) {
					$query->where(
						[
							'receiver_id' => $auth_user->id,
							'sender_id'   => $receiver_id,
							'last'        => 1,
						]
					);
				}
			)
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

	// showMessage   #####################################
	public function showMessages(int $chat_room_id):string
	{
		$messages = Messages::index($chat_room_id);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}

	// get chat rooms   #####################################
	public function getChatRooms(int $message_id):array
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::index(['messages.sender_id' => $auth_id, 'last' => 1],['messages.receiver_id' => $auth_id, 'last' => 1],$message_id)
			->latest('messages.id')
			->limit(3)
			->get();

		$chat_room_id = null;
		$new_receiver =null;
		$messages =[];

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('all_chat_rooms', 'chat_room_id'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('all_chat_rooms', 'chat_room_id','new_receiver','messages'))->render();

		return [
			'chat_rooms_view' => $chat_room_view,
			'chat_box_view'   => $chat_box_view,
		];
	}

	// showMessage   #####################################
	public function showOldMessages(Request $request, int $chat_room_id):string
	{
		$messages = Messages::index($chat_room_id,$request,true);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}
}
