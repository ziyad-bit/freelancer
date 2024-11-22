<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ChatRoomRequest;
use App\Http\Requests\TransactionRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse};

interface TransactionRepositoryInterface
{
	public function create_milestone(int $project_id,int $receiver_id):View;
	public function store_milestone(TransactionRequest $request):void;
}
