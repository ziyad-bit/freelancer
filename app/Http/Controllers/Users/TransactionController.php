<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Interfaces\Repository\TransactionRepositoryInterface;
use App\Traits\GetFunds;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\View\View;

class TransactionController extends Controller
{
	use GetFunds;

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
		$response=$this->transactionRepository->create_milestone($project_id, $receiver_id);

		if (!is_array($response)) {
			return $response;
		}

		return view('users.transaction.create')
				->with([
				'project_id'  => $response['project_id'],
				'receiver_id' => $response['receiver_id']
			]);
	}

	// MARK: checkout
	public function checkout(int $amount, int $project_id, int $receiver_id):View
	{
		$this->transactionRepository->checkout_transaction($amount, $project_id, $receiver_id);

		return view('users.transaction.form', compact('data', 'receiver_id', 'project_id'));
	}

	// MARK: release
	public function release(TransactionRequest $request):RedirectResponse
	{
		$response=  $this->transactionRepository->release_milestone($request);

		if ($response instanceof RedirectResponse) {
			return $response;		
		}

		return to_route('transaction.index')->with(['success' => 'milestone is released successfully']);
	}

	// MARK: get_withdraw
	public function get_withdraw():View
	{
		$user_funds = $this->get_total_money();

		return view('users.transaction.get_funds', compact('user_funds'));
	}

	// MARK: post_withdraw
	public function post_withdraw(TransactionRequest $request):RedirectResponse
	{
		$this->transactionRepository->post_funds($request);

		return to_route('transaction.get_withdraw')->with('success', 'the operation is successful');
	}
}
