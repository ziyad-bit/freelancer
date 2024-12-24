<?php

namespace App\Repositories;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProposalRequest;
use Illuminate\Support\Facades\{Auth, DB};
use App\Exceptions\GeneralNotFoundException;
use App\Interfaces\Repository\ProposalRepositoryInterface;

class ProposalRepository implements ProposalRepositoryInterface
{
	//MARK: storeProposal
	public function storeProposal(ProposalRequest $request): void
	{
		$data = $request->validated() + ['user_id' => Auth::id(), 'created_at' => now()];

		DB::table('proposals')->insert($data);
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
