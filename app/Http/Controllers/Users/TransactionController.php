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

	// MARK: release
	public function release(TransactionRequest $request):RedirectResponse
	{
		return $this->transactionRepository->release_milestone($request);
	}

	// MARK: get_withdraw
	public function get_withdraw():View
	{
		$user_funds = $this->transactionRepository->get_funds();

		return view('users.transaction.get_funds', compact('user_funds'));
	}

	// MARK: post_withdraw
	public function post_withdraw(TransactionRequest $request):RedirectResponse
	{
		$this->transactionRepository->post_funds($request);

		return to_route('transaction.get_withdraw')->with('success', 'the operation is successful');
	}
}
