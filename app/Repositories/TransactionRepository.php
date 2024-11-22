<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\DatabaseCache;
use Illuminate\Contracts\View\View;
use App\Classes\{ChatRooms, Messages};
use Illuminate\Support\Facades\{Auth, Cache, DB};
use App\Notifications\{AddUserToChatNotification};
use Illuminate\Http\{JsonResponse, RedirectResponse};
use App\Interfaces\Repository\ChatRoomRepositoryInterface;
use App\Http\Requests\{ChatRoomRequest, TransactionRequest};
use App\Interfaces\Repository\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
	use DatabaseCache;

	// MARK: create_milestone
	public function create_milestone(int $project_id,int $receiver_id):View
	{
		return view('users.transaction.create',compact('project_id','receiver_id'));
	}

	// MARK: store_milestone
	public function store_milestone(TransactionRequest $request): void
	{
		$data = $request->validated()+
				[
				'type'=>'milestone',
				'owner_id'=>Auth::id(),
				'trans_id'=>'4354353422',
				'created_at'=>now()
			];

		DB::table('transactions')->insert($data);

		DB::table('proposals')
			->where(['project_id'=>$request->project_id,'user_id'=>$request->receiver_id])
			->update(['finished'=>'in progress']);
	}
}
