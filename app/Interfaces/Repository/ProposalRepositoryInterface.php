<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProposalRequest;

interface ProposalRepositoryInterface
{
	public function storeProposal(ProposalRequest $request):void;
	public function updateProposal(ProposalRequest $request, int $id): null|RedirectResponse;
	public function deleteProposal(int $id): null|RedirectResponse;
}
