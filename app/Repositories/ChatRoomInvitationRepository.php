<?php

namespace App\Repositories;

use App\Classes\{ChatRooms, Messages};
use App\Exceptions\RecordExistException;
use App\Http\Requests\ChatRoomRequest;
use App\Interfaces\Repository\ChatRoomInvitationRepositoryInterface;
use App\Models\User;
use App\Notifications\AddUserToChatNotification;
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
		try {
			$user         = Auth::user();
			$chat_room_id = $request->chat_room_id;
			$receiver_id  = $request->user_id;
			$data         = $request->except('sender_id') + ['created_at' => now()];

			DB::beginTransaction();

			$user_in_chatroom = DB::table('chat_room_user')
				->where(['user_id' => $receiver_id, 'chat_room_id' => $chat_room_id])
				->first();

			if ($user_in_chatroom) {
				throw new RecordExistException('user');
			} else {
				DB::table('chat_room_user')->insert($data);
			}

			$receiver     = User::find($request->user_id);
			$view         = view('users.includes.notifications.send_user_invitation')
								->with('chat_room_id', $chat_room_id)
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

			Log::info('database commit and user sent an invitation');

			$this->forgetCache($receiver_id);
		} catch (RecordExistException $th) {
			abort(409, $th->getMessage());
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is ' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	// MARK: acceptInvitation
	public function postAcceptInvitationChatroom(ChatRoomRequest $request):void
	{
		try {
			$auth_id      = Auth::id();
			$chat_room_id = $request->chat_room_id;

			DB::beginTransaction();

			DB::table('messages')
				->where(['chat_room_id' => $chat_room_id, 'last' => 1])
				->update(['last' => 0]);

			DB::table('messages')
				->insert([
					'chat_room_id' => $chat_room_id,
					'receiver_id'  => $auth_id,
					'sender_id'    => $request->sender_id,
					'text'         => 'new_chat_room%',
					'created_at'   => now(),
				]);

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

	// MARK: getAcceptInvitation
	public function getAcceptInvitationChatroom(string $chat_room_id):array
	{
		$auth_id = Auth::id();

		/**
			we will get the chat room between the authenticated user and
			user who sent the invitation
		 * */
		$selected_chat_room = ChatRooms::index(
			['messages.chat_room_id' => $chat_room_id, 'last' => 1],
			[]
		);

		/**
		 * we will get the chat rooms with last received message
		 * or last sent message
		 */
		$all_chat_rooms = ChatRooms::index(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(4);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();
		$messages       = Messages::index($chat_room_id);

		return [
			'messages'           => $messages,
			'chat_room_id'       => $chat_room_id,
			'all_chat_rooms'     => $all_chat_rooms,
			'show_chatroom'      => true,
			'is_chatroom_page_1' => true,
		];
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
