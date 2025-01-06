<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProposalRequest;

interface ProposalRepositoryInterface
{
	public function storeProposal(ProposalRequest $request):void;
	public function showProposal(int $project_id):array;
	public function updateProposal(ProposalRequest $request, int $id): void;
	public function deleteProposal(int $id): void;
}
