<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Events\MessageEvent;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface};
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Traits\GetCursor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, DB, Notification};

class MessageRepository implements MessageRepositoryInterface
{
	use GetCursor;

	// MARK: storeMessage   
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):void
	{
		$auth_user   = Auth::user();
		$data        = $request->safe()->only(['chat_room_id', 'text', 'receiver_id']) +
						['created_at' => now(), 'sender_id' => $auth_user->id];

		$receiver_id = $request->receiver_id;

		DB::table('messages')
			->where(
				[
					'chat_room_id' => $request->chat_room_id,
					'last'         => 1,
				]
			)
			->update(['last' => 0]);

		$message_id = DB::table('messages')->insertGetId($data);

		$files = $fileRepository->insert_file($request, 'message_files', 'message_id', $message_id);

		broadcast(new MessageEvent($data, $files))->toOthers();

		$notif_view = view('users.includes.notifications.send', compact('data'))->render();
		$user       = User::find($request->receiver_id);

		Notification::send($user, new NewMessageNotification($data, $auth_user->name, $auth_user->image, $notif_view));

		if (Cache::has('notifs_' . $receiver_id)) {
			Cache::forget('notifs_' . $receiver_id);
			Cache::increment('notifs_count_' . $receiver_id);
		}
	}

	// MARK: showMessage  
	public function showMessages(int $chat_room_id):string
	{
		$messages = Messages::index($chat_room_id);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}

	// MARK: show Old Msgs   
	public function showOldMessages(Request $request, int $chat_room_id):string
	{
		$messages = Messages::index($chat_room_id, $request, true);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}
}
