<?php

namespace App\Repositories;

use App\Http\Requests\ProposalRequest;
use App\Interfaces\Repository\ProposalRepositoryInterface;
use Illuminate\Support\Facades\{Auth, DB};

class ProposalRepository implements ProposalRepositoryInterface
{
	####################################   storeProject   #####################################
	public function storeProposal(ProposalRequest $request): void
	{
		$data = $request->validated() + ['user_id' => Auth::id(), 'created_at' => now()];

		DB::table('proposals')->insert($data);
	}

	####################################    updateProposal   #####################################
	public function updateProposal(ProposalRequest $request, int $id): void
	{
		$data = $request->validated();

		DB::table('proposals')->where('id', $id)->update($data);
	}

	####################################    deleteProposal   #####################################
	public function deleteProposal(int $id): void
	{
		DB::table('proposals')->where('id', $id)->delete();
	}
}
