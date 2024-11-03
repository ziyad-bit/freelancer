<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProposalRequest;
use App\Interfaces\Repository\ProposalRepositoryInterface;
use Illuminate\Http\{JsonResponse, RedirectResponse};

class ProposalController extends Controller
{
	public function __construct(private ProposalRepositoryInterface $ProposalRepository)
	{
		$this->middleware('auth');
	}

	//MARK: store  
	public function store(ProposalRequest $request):RedirectResponse
	{
		$this->ProposalRepository->storeProposal($request);

		return to_route('project.show', $request->input('project_id'))->with('success', 'you added proposal successfully');
	}

	//MARK: update   
	public function update(ProposalRequest $request, int $id):JsonResponse
	{
		$this->ProposalRepository->updateProposal($request, $id);

		return response()->json(['success' => 'you updated proposal successfully']);
	}

	//MARK: destroy  
	public function destroy(int $id):JsonResponse
	{
		$this->ProposalRepository->deleteProposal($id);

		return response()->json(['success' => 'you delete proposal successfully']);
	}
}
