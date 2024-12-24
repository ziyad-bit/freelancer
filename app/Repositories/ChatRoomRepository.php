<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\DatabaseCache;
use Illuminate\Support\Facades\Log;
use App\Classes\{ChatRooms, Messages};
use App\Http\Requests\ChatRoomRequest;
use App\Exceptions\RecordExistException;
use Illuminate\Support\Facades\{Auth, DB};
use App\Exceptions\GeneralNotFoundException;
use App\Notifications\AddUserToChatNotification;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use App\Interfaces\Repository\ChatRoomRepositoryInterface;

class ChatRoomRepository implements ChatRoomRepositoryInterface
{
	use DatabaseCache;

	// MARK: indexChatroom
	public function indexChatroom():array
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(3)
		->get();

		$messages     = [];
		$chat_room_id = null;

		if ($all_chat_rooms->count() > 0) {
			$messages = Messages::index($all_chat_rooms[0]->chat_room_id);
		}

		return [
			'messages'           => $messages,
			'chat_room_id'       => $chat_room_id,
			'all_chat_rooms'     => $all_chat_rooms,
			'show_chatroom'      => true,
			'is_chatroom_page_1' => true,
		];
	}

	// MARK: fetch
	public function fetchWithSelectedUser(int $receiver_id): array|RedirectResponse
	{
		$messages     = [];
		$chat_room_id = null;

		$receiver = DB::table('users')->find($receiver_id, ['name', 'image', 'id']);
		if (!$receiver) {
			throw new GeneralNotFoundException('user not found');
		}

		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1]
		)
		->latest('messages.id')
		->limit(4);

		$selected_chat_room = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'messages.receiver_id' => $receiver_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'messages.sender_id' => $receiver_id, 'last' => 1]
		);

		$all_chat_rooms = $all_chat_rooms->union($selected_chat_room)->get();

		foreach ($all_chat_rooms as $chat_room) {
			if ($chat_room->receiver_id === $receiver_id) {
				$chat_room_id = $chat_room->chat_room_id;

				break;
			}
		}

		$created_at = now();

		if (!$chat_room_id) {
			DB::table('chat_rooms')
				->insert(
					[
						'id'          => Str::uuid(),
						'owner_id'    => $auth_id,
						'created_at'  => $created_at,
					]
				);

			$chat_room_id = DB::table('chat_rooms')
				->where(['created_at' => $created_at, 'owner_id' => $auth_id])
				->value('id');

			$message_id = DB::table('messages')
				->insertGetId([
					'chat_room_id' => $chat_room_id,
					'receiver_id'  => $receiver_id,
					'sender_id'    => $auth_id,
					'text'         => 'new_chat_room%',
					'created_at'   => now(),
				]);

			DB::table('chat_room_user')
				->insert([
					['chat_room_id' => $chat_room_id, 'user_id' => $auth_id],
					['chat_room_id' => $chat_room_id, 'user_id' => $receiver_id],
				]);
		} else {
			$messages = Messages::index($chat_room_id);
		}

		return [
			'messages'           => $messages,
			'chat_room_id'       => $chat_room_id,
			'all_chat_rooms'     => $all_chat_rooms,
			'receiver'           => $receiver,
			'message_id'         => $message_id,
			'show_chatroom'      => true,
			'is_chatroom_page_1' => true,
		];
	}

	//MARK: get chat rooms
	public function getChatRooms(int $message_id):array
	{
		$auth_id        = Auth::id();
		$all_chat_rooms = ChatRooms::fetch(
			['messages.sender_id' => $auth_id, 'last' => 1],
			['messages.receiver_id' => $auth_id, 'last' => 1],
			$message_id
		)
		->latest('messages.id')
		->limit(4)
		->get();

		$chat_room_id       = null;
		$messages           = [];
		$show_chatroom      = false;
		$is_chatroom_page_1 = true;

		$chat_room_view = view('users.includes.chat.index_chat_rooms', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'is_chatroom_page_1'))->render();
		$chat_box_view  = view('users.includes.chat.index_chat_boxes', compact('show_chatroom', 'all_chat_rooms', 'chat_room_id', 'messages'))->render();

		return [
			'chat_rooms_view' => $chat_room_view,
			'chat_box_view'   => $chat_box_view,
		];
	}

	public function get_chatroom_users()
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

			if (!$user_in_chatroom ) {
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
		}catch (RecordExistException $th) {
			abort(409, $th->getMessage());
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is'.$th->getMessage());

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
