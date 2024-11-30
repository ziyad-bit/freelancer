<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Interfaces\Repository\TransactionRepositoryInterface;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\View\View;

class TransactionController extends Controller
{
	public function __construct(private TransactionRepositoryInterface $transactionRepository)
	{
		$this->middleware('auth');
	}
	// MARK: index
	public function index(string $created_at = null):View|JsonResponse
	{
		$transactions = $this->transactionRepository->index_transaction($created_at);

		if (request()->ajax()) {
			$view = view('users.includes.transaction.table', compact('transactions'))->render();

			return response()->json(['view' => $view]);
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
	public function release(TransactionRequest $request):RedirectResponse
	{
		$this->transactionRepository->release_milestone($request);

		return to_route('transaction.index')->with(['success' => 'milestone is released successfully']);
	}

	// MARK: show
	public function get_withdraw():View
	{
		return $this->transactionRepository->get_funds();
	}

	// MARK: edit
	public function post_withdraw(int $id):View
	{
		return view('');
	}
}
