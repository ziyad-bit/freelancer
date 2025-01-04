<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\DatabaseCache;
use Illuminate\Http\RedirectResponse;
use App\Classes\{ChatRooms, Messages};
use App\Http\Requests\ChatRoomRequest;
use Illuminate\Support\Facades\{Auth, DB, Log};
use App\Notifications\AddUserToChatNotification;
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Exceptions\{GeneralNotFoundException, RecordExistException};
use App\Interfaces\Repository\ChatRoomInvitationRepositoryInterface;
use Illuminate\Support\Collection;

class ChatRoomInvitationRepository implements ChatRoomInvitationRepositoryInterface
{
	use DatabaseCache;

	//MARK: get Users
	public function get_chatroom_users():Collection
	{
		return DB::table('users')
			->select('users.id', 'name', 'image')
			->join('chat_room_user as cru1', 'cru1.user_id', '=', 'users.id')
			->join('chat_room_user as cru2', 'cru1.chat_room_id', '=', 'cru2.chat_room_id')
			->where('cru2.user_id', Auth::id())
			->where('users.id', '!=', Auth::id())
			->get();
	}
	
	//MARK: sendInvitation
	public function sendInvitation(ChatRoomRequest $request):void
	{
		try {
			$chat_room_id = $request->chat_room_id;
			$receiver_id  = $request->user_id;
			$data         = $request->validated() + ['created_at' => now()];

			DB::beginTransaction();

			$user_in_chatroom = DB::table('chat_room_user')
				->where(['user_id' => $receiver_id, 'chat_room_id' => $chat_room_id])
				->first();

			if (!$user_in_chatroom) {
				DB::table('chat_room_user')->insert($data);
			} else {
				throw new RecordExistException('user');
			}

			$user         = Auth::user();
			$receiver     = User::find($request->user_id);
			$view         = view('users.includes.notifications.send_user_invitation')
							->with('chat_room_id')
							->render();

			$receiver->notify(
				new AddUserToChatNotification(
					$chat_room_id,
					$user->name,
					$user->image,
					$view
				)
			);

			DB::commit();

			Log::info('database commit and user sent an invitation to the user_id: ' . $receiver_id . ' to join the chatroom_id: ' . $chat_room_id);

			$this->forgetCache($receiver_id);
		} catch (RecordExistException $th) {
			abort(409, $th->getMessage());
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	// MARK: acceptInvitation
	public function postAcceptInvitationChatroom(ChatRoomRequest $request):void
	{
		$auth_id      = Auth::id();
		$chat_room_id = $request->chat_room_id;

		$chat_room_user_query = DB::table('chat_room_user')
			->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id]);

		$chat_room  = $chat_room_user_query->first();

		if (!$chat_room) {
			throw new GeneralNotFoundException('chatroom');
		}

		$chat_room_user_query->update(['decision' => 'approved']);

		DB::table('notifications')
			->where(['data->chat_room_id' => $chat_room_id, 'notifiable_id' => $auth_id])
			->delete();

		$this->forgetCache($auth_id);
	}

	// MARK: getAcceptInvitation
	public function getAcceptInvitationChatroom(int $chat_room_id):array
	{
		$auth_id = Auth::id();

		$selected_chat_room = ChatRooms::fetch(
			['messages.chat_room_id' => $chat_room_id, 'last' => 1],
			[]
		);

		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(4);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();
		$messages       = Messages::index($chat_room_id);

		return [
			'messages'       => $messages,
			'chat_room_id'   => $chat_room_id,
			'all_chat_rooms' => $all_chat_rooms,
		];
	}

	// MARK: refuseInvitation
	public function refuseInvitationChatroom(ChatRoomRequest $request): void
	{
		$chat_room_id = $request->chat_room_id;
		$auth_id      = Auth::id();

		$chat_room_user_query = DB::table('chat_room_user')
			->where(['chat_room_id' => $chat_room_id, 'user_id' => $auth_id]);

		$chat_room_user = $chat_room_user_query->first();

		if (!$chat_room_user) {
			throw new GeneralNotFoundException('chatroom');
		}

		$chat_room_user_query->delete();

		DB::table('notifications')
			->where(['data->chat_room_id' => $chat_room_id, 'notifiable_id' => $auth_id])
			->delete();

		$this->forgetCache($auth_id);
	}
}
