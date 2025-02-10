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
		$this->middleware(['auth', 'verifyEmail']);
		$this->middleware('proposal')->except(['store', 'show']);
	}

	//MARK: store
	public function store(ProposalRequest $request):RedirectResponse
	{
		$this->ProposalRepository->storeProposal($request);

		return to_route('project.show', $request->project_id)->with('success', 'you added proposal successfully');
	}

	//MARK: store
	public function show(int $project_id):JsonResponse
	{
		$data = $this->ProposalRepository->showProposal($project_id);

		return response()->json($data);
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

		return response()->json(['success' => 'you deleted proposal successfully']);
	}
}
