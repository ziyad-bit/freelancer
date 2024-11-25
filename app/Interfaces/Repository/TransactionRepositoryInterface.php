<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\TransactionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\CursorPaginator;

interface TransactionRepositoryInterface
{
	public function index_transaction():CursorPaginator;
	public function create_milestone(int $project_id, int $receiver_id):View;
	public function checkout_transaction(int $amount, int $project_id, int $receiver_id):View;
	public function store_milestone(TransactionRequest $request):void;
}
