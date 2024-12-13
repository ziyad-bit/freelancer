<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\TransactionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
	public function index_transaction(string $created_at = null):Collection;
	public function create_milestone(int $project_id, int $receiver_id):View|array;
	public function checkout_transaction(int $amount):void;
	public function release_milestone(TransactionRequest $request):? RedirectResponse;
	public function post_funds(TransactionRequest $request):void;
}
