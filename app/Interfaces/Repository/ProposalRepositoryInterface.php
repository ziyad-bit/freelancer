<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProposalRequest;

interface ProposalRepositoryInterface
{
	public function storeProposal(ProposalRequest $request):void;
	public function updateProposal(ProposalRequest $request,int $id):void;
}
