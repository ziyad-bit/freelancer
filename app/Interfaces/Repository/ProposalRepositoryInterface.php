<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProposalRequest;
use Illuminate\Http\RedirectResponse;

interface ProposalRepositoryInterface
{
	public function storeProposal(ProposalRequest $request):void;
	public function updateProposal(ProposalRequest $request, int $id): null|RedirectResponse;
	public function deleteProposal(int $id): null|RedirectResponse;
}
