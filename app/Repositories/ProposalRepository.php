<?php

namespace App\Repositories;

use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\ProposalRequest;
use App\Interfaces\Repository\ProposalRepositoryInterface;
use App\Traits\GetCursor;
use Illuminate\Support\Facades\{Auth, DB};

class ProposalRepository implements ProposalRepositoryInterface
{
	use GetCursor;

	//MARK: storeProposal
	public function storeProposal(ProposalRequest $request): void
	{
		$data = $request->validated() + ['user_id' => Auth::id(), 'created_at' => now()];

		DB::table('proposals')->insert($data);
	}

	//MARK: storeProposal
	public function showProposal(int $project_id): array
	{
		$proposals = DB::table('proposals')
					->select(
						'projects.user_id as project_user_id',
						'proposals.*',
						'location',
						'card_num',
						'name',
						'slug',
						DB::raw('IFNULL(ROUND(AVG(rate), 1),0) as review'),
					)
					->join('projects', 'projects.id', '=', 'proposals.project_id')
					->join('users', 'users.id', '=', 'proposals.user_id')
					->join('user_infos', 'users.id', '=', 'user_infos.user_id')
					->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
					->where('proposals.project_id', $project_id)
					->groupBy('proposals.id')
					->orderBy('proposals.id')
					->cursorPaginate(3);

		$cursor = $this->getCursor($proposals);
		$view   = view('users.project.show_proposals', compact('proposals'))->render();

		return ['view' => $view, 'cursor' => $cursor];
	}

	//MARK: updateProposal
	public function updateProposal(ProposalRequest $request, int $id): void
	{
		$data = $request->validated();

		$proposal_query = DB::table('proposals')->where('id', $id);
		$proposal       = $proposal_query->first();

		if (!$proposal) {
			throw new GeneralNotFoundException('proposal');
		}

		$proposal_query->update($data);
	}

	//MARK: deleteProposal
	public function deleteProposal(int $id): void
	{
		$proposal_query = DB::table('proposals')->where('id', $id);
		$proposal       = $proposal_query->first();

		if (!$proposal) {
			throw new GeneralNotFoundException('proposal');
		}

		$proposal_query->delete();
	}
}
