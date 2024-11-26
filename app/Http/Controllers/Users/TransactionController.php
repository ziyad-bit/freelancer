<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Interfaces\Repository\TransactionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
	public function __construct(private TransactionRepositoryInterface $transactionRepository)
	{
		$this->middleware('auth');
	}
	// MARK: index
	public function index(string $created_at=null):View|JsonResponse
	{
		$transactions = $this->transactionRepository->index_transaction($created_at);

		if (request()->ajax()) {
			$view = view('users.includes.transaction.table',compact('transactions'))->render();
			return response()->json(['view'=>$view]);
		}

		return view('users.transaction.index', compact('transactions'));
	}

	// MARK: create
	public function create(int $project_id, int $receiver_id):View
	{
		return $this->transactionRepository->create_milestone($project_id, $receiver_id);
	}

	// MARK: checkout
	public function checkout(int $amount, int $project_id, int $receiver_id):View
	{
		return $this->transactionRepository->checkout_transaction($amount, $project_id, $receiver_id);
	}

	// MARK: store
	public function store(TransactionRequest $request):RedirectResponse
	{
		$this->transactionRepository->store_milestone($request);

		return to_route('project.show', $request->project_id)->with(['success' => 'milestone is created successfully']);
	}

	// MARK: show
	public function show(int $id):View
	{
		return view('');
	}

	// MARK: edit
	public function edit(int $id):View
	{
		return view('');
	}

	// MARK: update
	public function update($request, int $id):RedirectResponse
	{
		return to_route('');
	}

	// MARK: destroy
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
