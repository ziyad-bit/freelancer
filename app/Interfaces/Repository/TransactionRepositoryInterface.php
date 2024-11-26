<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use App\Http\Requests\TransactionRequest;
use Illuminate\Pagination\CursorPaginator;

interface TransactionRepositoryInterface
{
	public function index_transaction(string $created_at=null):Collection;
	public function create_milestone(int $project_id, int $receiver_id):View;
	public function checkout_transaction(int $amount, int $project_id, int $receiver_id):View;
	public function store_milestone(TransactionRequest $request):void;
}
