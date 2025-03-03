<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReleaseRequest;
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DebatesController extends Controller
{
	public function __construct(private DebateRepositoryInterface $debateRepository)
	{
		$this->middleware('auth:admins');
		$this->middleware('transaction_debate')->only('update');
	}

	// MARK: index
	public function index():View
	{
		$debates = $this->debateRepository->indexDebate();

		return view('admins.debate.index', compact('debates'));
	}

	// MARK: show
	public function show(int $id):View
	{
		$debate = $this->debateRepository->showDebate($id);

		return view('admins.debate.show', compact('debate'));
	}

	// MARK: store
	public function access_chat(int $initiator_id, int $opponent_id, int $message_id=0):View|JsonResponse
	{
		$messages = $this->debateRepository->accessChatDebate($initiator_id, $opponent_id, $message_id);

		if (request()->ajax()) {
			return response()->json(['messages' => $messages]);
		}

		return view('admins.debate.chat', compact('messages','opponent_id','initiator_id'));
	}

	// MARK: update
	public function update(ReleaseRequest $request,string $transaction_id):RedirectResponse
	{
		$this->debateRepository->updateDebate($request,$transaction_id);

		return redirect()->back()->with('success', 'you released milestone successfully');
	}
}
