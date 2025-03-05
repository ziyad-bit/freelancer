<?php

namespace App\Repositories;

use App\Events\AddUserToChatEvent;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomInvitationRepositoryInterface;
use App\Traits\DatabaseCache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB, Log};

class ChatRoomInvitationRepository implements ChatRoomInvitationRepositoryInterface
{
	use DatabaseCache;

	//MARK: get Users
	public function get_chatroom_users(string $chat_room_id):Collection
	{
		$searchName = request('searchName');

		/**
			*get all users who are in other chat rooms to choose one from them
			* to send an invitation to join the chat room
		 */
		return DB::table('users')
			->select('users.id', 'name', 'image')
			->join('chat_room_user as cru1', 'cru1.user_id', '=', 'users.id')
			->join('chat_room_user as cru2', 'cru1.chat_room_id', '=', 'cru2.chat_room_id')
			->where('cru2.user_id', Auth::id())
			->whereNotIn('cru1.user_id', function ($query) use ($chat_room_id) {
				$query->select('user_id')
					->from('chat_room_user')
					->where('chat_room_id', $chat_room_id);
			})
			->when($searchName, fn ($query) => $query->where('name', 'like', "$searchName%"))
			->distinct()
			->limit(10)
			->get();
	}

	//MARK: sendInvitation
	public function sendInvitation(ChatRoomRequest $request):void
	{
		// try {
		$chat_room_id = $request->chat_room_id;
		$receiver_id  = $request->user_id;
		$data         = $request->except('sender_id') + ['created_at' => now()];

		DB::beginTransaction();

		event(new AddUserToChatEvent($data, $chat_room_id, $receiver_id));

		DB::commit();

		Log::info('database commit and user sent an invitation');

		$this->forgetCache($receiver_id);
		// }catch (\Throwable $th) {
		// 	DB::rollBack();
		// 	Log::critical('database rollback and error is ' . $th->getMessage());

		// 	abort(500, 'something went wrong');
		// }
	}

	// MARK: acceptInvitation
	public function postAcceptInvitationChatroom(ChatRoomRequest $request):void
	{
		try {
			$auth_id      = Auth::id();
			$chat_room_id = $request->chat_room_id;

			DB::beginTransaction();

			DB::table('chat_room_user')
				->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id])
				->update(['decision' => 'approved']);

			DB::table('notifications')
				->where(['data->chat_room_id' => $chat_room_id, 'notifiable_id' => $auth_id])
				->delete();

			DB::commit();

			$this->forgetCache($auth_id);
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is ' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	// MARK: refuseInvitation
	public function refuseInvitationChatroom(ChatRoomRequest $request): void
	{
		$chat_room_id = $request->chat_room_id;
		$auth_id      = Auth::id();

		DB::table('chat_room_user')
			->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id])
			->delete();

		DB::table('notifications')
			->where(['data->chat_room_id' => $chat_room_id, 'notifiable_id' => $auth_id])
			->delete();

		$this->forgetCache($auth_id);
	}
}
