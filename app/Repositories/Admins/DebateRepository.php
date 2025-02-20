<?php

namespace App\Repositories\Admins;

use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\{DebateRequest};
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use App\Traits\{Slug};
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\{LengthAwarePaginator};
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

	//MARK: accessChat
	public function accessChatDebate(int $initiator_id, int $opponent_id,int $message_id=null):Collection
	{
		return DB::table('messages')
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
			->latest('messages.created_at')
			->limit(4)
			->get();
	}

	//MARK: storeDebate
	public function storeDebate(DebateRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
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

	//MARK: editDebate
	public function activeDebate(int $id):void
	{
		$Debate_query = DB::table('Debate_infos')->where('Debate_id', $id);

		if (!$Debate_query->exists()) {
			throw new GeneralNotFoundException('Debate');
		}

		$Debate_query->update(['active' => 'active']);
	}

	//MARK: editDebate
	public function editDebate(string $id):stdClass
	{
		$Debate = DB::table('Debate_infos')->where('Debate_id', $id);

		if (!$Debate) {
			throw new GeneralNotFoundException('Debate');
		}

		return $Debate->first();
	}

	//MARK:   updateDebate
	public function updateDebate(
		DebateRequest $request,
		FileRepositoryInterface $fileRepository,
		SkillRepositoryInterface $skillRepository,
		string $slug,
	):void {
		try {
			$Debate_data      = $request->safe()->only(['title', 'content']) + ['user_id' => Auth::id(), 'created_at' => now()];
			$Debate_info_data = $request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

			$Debate_query = DB::table('Debates')->where('slug', $slug);
			$Debate       = $Debate_query->first();

			if (!$Debate) {
				throw new GeneralNotFoundException('Debate');
			}

			DB::beginTransaction();

			$Debate_query->update($Debate_data);

			DB::table('Debate_infos')->where('Debate_id', $Debate->id)->update($Debate_info_data);

			$fileRepository->insert_file($request, 'Debate_files', 'Debate_id', $Debate->id);

			$skillRepository->storeSkill($request, 'Debate_skill', 'Debate_id', $Debate->id);
			DB::commit();

			Log::info('database commit');
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is' . $th->getMessage());

			abort(500, 'something went wrong');
		}
	}

	//MARK: deleteDebate
	public function deleteDebate(string $slug):void
	{
		$Debate_query = DB::table('Debates')->where('slug', $slug);
		$Debate_id    = $Debate_query->value('id');

		if (!$Debate_id) {
			throw new GeneralNotFoundException('Debate');
		}

		$Debate_query->delete();
	}
}
