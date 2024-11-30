<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\TransactionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
	public function index_transaction(string $created_at = null):Collection;
	public function create_milestone(int $project_id, int $receiver_id):View;
	public function checkout_transaction(int $amount, int $project_id, int $receiver_id):View;
	public function release_milestone(TransactionRequest $request):void;
	public function get_funds():View;
	public function post_funds(TransactionRequest $request):void;
}
