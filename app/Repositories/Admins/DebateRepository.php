<?php

namespace App\Repositories\Admins;

use App\Classes\Debate;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\{DebateRequest};
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use App\Traits\{Slug};
use Illuminate\Pagination\{LengthAwarePaginator};
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

	//MARK: storeDebate
	public function storeDebate(DebateRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void
	{
		try {
			DB::beginTransaction();
			$slug = $this->createSlug('Debates', 'title', $request->title);

			$Debate_data = $request->safe()->only(['title', 'content']) +
						[
							'admin_id'    => Auth::guard('admins')->id(),
							'created_at'  => now(),
							'slug'        => $slug,
						];

			$Debate_id  = DB::table('Debates')->insertGetId($Debate_data);

			$Debate_info_data = ['Debate_id' => $Debate_id]  +
					$request->safe()->only(['num_of_days', 'min_price', 'max_price', 'exp']);

			DB::table('Debate_infos')->insert($Debate_info_data);

			$fileRepository->insert_file($request, 'Debate_files', 'Debate_id', $Debate_id);

			$skillRepository->storeSkill($request, 'Debate_skill', 'Debate_id', $Debate_id);
			DB::commit();

			Log::info('database commit');
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::critical('database rollback and error is ' . $th->getMessage());

			abort(500, 'something went wrong');
		}
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
				'opponent.name as opponent_name',
				'opponent.slug as opponent_slug',
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
			->where('debates.id',$id)
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
