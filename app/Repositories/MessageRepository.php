<?php

namespace App\Repositories;

use App\Classes\Messages;
use App\Events\MessageEvent;
use App\Http\Requests\MessageRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, MessageRepositoryInterface};
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Traits\DatabaseCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Notification};

class MessageRepository implements MessageRepositoryInterface
{
	use DatabaseCache;
	// MARK: storeMessage
	public function storeMessage(MessageRequest $request, FileRepositoryInterface $fileRepository):array
	{
		try {
			$text        = $request->safe()->__get('text');
			$enc_text    = $request->text;
			$receiver_id = $request->receiver_id;
			$auth_user   = Auth::user();
			$data        = $request->safe()->only(['chat_room_id', 'receiver_id']) +
						['created_at' => now(), 'sender_id' => $auth_user->id, 'text' => $enc_text];

			DB::beginTransaction();

			//update last message
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

			$data['text'] = $text;

			//send message to other user by using broadcast
			$view_msg = view('users.includes.chat.send_message', compact('data', 'files'))->render();

			broadcast(new MessageEvent($data, $view_msg, $auth_user->name))->toOthers();

			Log::info('user sent message');

			//send notification to other user
			$notif_view = view('users.includes.notifications.send', compact('data'))->render();
			$user       = User::find($receiver_id);

			$data['text'] = $enc_text;

			Notification::send($user, new NewMessageNotification($data, $auth_user->name, $auth_user->image, $notif_view));

			DB::commit();
			Log::info('database commit and user will receive notification message');

			$this->forgetCache($receiver_id);

			return ['view' => $view_msg, 'text' => $text];
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	// MARK: showMessage
	//when user click on chat room
	public function showMessages(string $chat_room_id):string
	{
		$messages = Messages::index($chat_room_id);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}

	// MARK: show Old Msgs
	public function showOldMessages(string $chat_room_id,int $message_id):string
	{
		$messages = Messages::index($chat_room_id,$message_id);

		return view('users.includes.chat.index_msgs', compact('messages'))->render();
	}
}
