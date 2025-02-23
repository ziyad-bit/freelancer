<?php

namespace App\Repositories\Admins;

use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\{ReleaseRequest};
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use App\Traits\Slug;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB, Log};
use stdClass;

class DebateRepository implements DebateRepositoryInterface
{
	use Slug;

	//MARK: indexDebate
	public function indexDebate():LengthAwarePaginator
	{
		return DB::table('debates')
			->select(
				'debates.id',
				'status',
				'debates.created_at',
				'initiator.name as initiator_name',
				'opponent.name as opponent_name'
			)
			->join('users as initiator', 'debates.initiator_id', '=', 'initiator.id')
			->join('users as opponent', 'debates.opponent_id', '=', 'opponent.id')
			->latest('debates.id')
			->paginate(10);
	}

	//MARK: showDebate
	public function showDebate(int $id):stdClass
	{
		return DB::table('debates')
			->select(
				'debates.id',
				'debates.description',
				'status',
				'debates.created_at',
				'initiator.name as initiator_name',
				'initiator.slug as initiator_slug',
				'initiator.id as initiator_id',
				'opponent.name as opponent_name',
				'opponent.slug as opponent_slug',
				'opponent.id as opponent_id',
				'amount',
				'transactions.id as transaction_id',
				'title',
				'content',
				'num_of_days',
				'projects.slug',
			)
			->join('users as initiator', 'debates.initiator_id', '=', 'initiator.id')
			->join('users as opponent', 'debates.opponent_id', '=', 'opponent.id')
			->join('transactions', 'debates.transaction_id', '=', 'transactions.id')
			->join('projects', 'debates.project_id', '=', 'projects.id')
			->join('project_infos', 'projects.id', '=', 'project_infos.project_id')
			->where('debates.id', $id)
			->latest('debates.id')
			->first();
	}

	//MARK: accessChat
	public function accessChatDebate(int $initiator_id, int $opponent_id, int $message_id = null):Collection|string
	{
		$messages = DB::table('messages')
			->select(
				'name as sender_name',
				'slug',
				'image as sender_image',
				'text',
				'messages.id',
				'messages.created_at',
				DB::raw('GROUP_CONCAT(message_files.file order by message_files.file) as files_name'),
				DB::raw('GROUP_CONCAT(message_files.type order by message_files.file) as files_type'),
			)
			->join('users', 'messages.sender_id', '=', 'users.id')
			->leftJoin('message_files', 'messages.id', '=', 'message_files.message_id')
			->whereIn('chat_room_id', function ($query) use ($initiator_id, $opponent_id) {
				$query->from('chat_room_user')
					->select('chat_room_id')
					->whereIn('user_id', [$initiator_id, $opponent_id])
					->groupBy('chat_room_id')
					->havingRaw('COUNT(DISTINCT user_id) = 2');
			})
			->when(
				$message_id,
				function ($query) use ($message_id) {
					$query->Where('messages.id', '<', $message_id);
				}
			)
			->groupBy('messages.id')
			->latest('messages.id')
			->limit(4)
			->get();

		if (request()->ajax()) {
			return view('users.includes.chat.index_msgs', compact('messages'))->render();
		}

		return $messages;
	}

	//MARK: updateDebate
	public function updateDebate(ReleaseRequest $request, string $debate_id):void
	{
		try {
			$debate_query   = DB::table('debates')->where('id', $debate_id);
			$transaction_id = $debate_query->lockForUpdate()->value('transaction_id');

			if (!$transaction_id) {
				throw new GeneralNotFoundException('debate');
			}

			DB::beginTransaction();

			$debate_query->update(['status' => 'finished']);

			DB::table('transactions')
				->where('id', $transaction_id)
				->update([
					'type'        => 'release',
					'receiver_id' => $request->receiver_id,
					'owner_id'    => Auth::id(),
				]);

			DB::commit();
			Log::info('database commit');
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}
}
