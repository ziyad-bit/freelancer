<?php

namespace App\Repositories;

use App\Http\Requests\ProposalRequest;
use App\Interfaces\Repository\ProposalRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB};

class ProposalRepository implements ProposalRepositoryInterface
{
	//MARK: storeProposal
	public function storeProposal(ProposalRequest $request): void
	{
		$data = $request->validated() + ['user_id' => Auth::id(), 'created_at' => now()];

		DB::table('proposals')->insert($data);
	}

	//MARK: updateProposal
	public function updateProposal(ProposalRequest $request, int $id): null|RedirectResponse
	{
		$data = $request->validated();

		$proposal_query = DB::table('proposals')->where('id', $id);
		$proposal       = $proposal_query->first();

		if (!$proposal) {
			return redirect()->back()->with('error', 'proposal not found');
		}

		$proposal_query->update($data);

		return null;
	}

	//MARK: deleteProposal
	public function deleteProposal(int $id): null|RedirectResponse
	{
		$proposal_query = DB::table('proposals')->where('id', $id);
		$proposal       = $proposal_query->first();

		if (!$proposal) {
			return redirect()->back()->with('error', 'proposal not found');
		}

		$proposal_query->delete();

		return null;
	}
}
