<?php

namespace App\Repositories;

use App\Http\Requests\TransactionRequest;
use App\Interfaces\Repository\TransactionRepositoryInterface;
use App\Models\User;
use App\Notifications\{MilestoneNotification};
use App\Traits\{DatabaseCache, GetFunds, Payment};
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\{Collection,Str};

class TransactionRepository implements TransactionRepositoryInterface
{
	use DatabaseCache ,Payment,GetFunds;

	//MARK: index_transaction
	public function index_transaction(string $created_at = null):Collection
	{
		$auth_id      = Auth::id();

		return DB::table('transactions')
			->select(
				'transactions.id',
				'transactions.type',
				'transactions.amount',
				'transactions.created_at as date',
				'transactions.receiver_id',
				'transactions.project_id',
				'projects.title as project_title',
				'receiver.name as receiver_name',
				'owner.name as owner_name',
			)
			->leftJoin('projects', 'projects.id', '=', 'transactions.project_id')
			->leftJoin('users as receiver', 'receiver.id', '=', 'transactions.receiver_id')
			->join('users as owner', 'owner.id', '=', 'transactions.owner_id')
			->where('receiver_id', $auth_id)
			->orwhere(fn ($query) => $query->where('transactions.owner_id', $auth_id))
			->when($created_at, fn ($query) => $query->where('date', '<', $created_at))
			->latest('date')
			->limit(8)
			->get();
	}

	// MARK: create_milestone
	public function create_milestone(int $project_id, int $receiver_id):View|array
	{
		try {
			$resourcePath = request('resourcePath');
			$id           = request('id');

			if ($id && $resourcePath) {
				$url           = 'https://eu-test.oppwa.com/' . $resourcePath;
				$url          .= '?entityId=8a8294174b7ecb28014b9699220015ca';

				$status = $this->getPaymentStatus($url);

				if (isset($status['id'])) {
					$data  = [
						'type'        => 'milestone',
						'receiver_id' => $receiver_id,
						'project_id'  => $project_id,
						'amount'      => $status['amount'],
						'owner_id'    => Auth::id(),
						'id'          => $status['id'],
						'created_at'  => now(),
					];
					DB::beginTransaction();
					DB::table('transactions')->insert($data);

					DB::table('proposals')
						->where(['project_id' => $project_id, 'user_id' => $receiver_id])
						->update(['finished' => 'in progress']);

					DB::commit();

					$release  = false;
					$receiver = User::find($receiver_id);
					$user     = Auth::user();
					$view     = view('users.includes.notifications.milestone', compact('data', 'release'))->render();

					$receiver->notify(new MilestoneNotification($data['amount'], $user->name, $user->image, $view));
					Log::info('user created milestone');

					$this->forgetCache($receiver_id);

					$msg = 'the operation is finished successfully';

					return view('users.transaction.create', compact('project_id', 'receiver_id', 'msg'));
				} else {
					$error = 'the operation is failed';

					return view('users.transaction.create', compact('project_id', 'receiver_id', 'error'));
				}
			}

			return ['project_id' => $project_id, 'receiver_id' => $receiver_id];
		} catch (\Throwable) {
			DB::rollBack();
			abort(500, 'something went wrong');
		}
	}

	// MARK: checkout
	public function checkout_transaction(int $amount):void
	{
		$url  = 'https://eu-test.oppwa.com/v1/checkouts';
		$data = 'entityId=8a8294174b7ecb28014b9699220015ca' .
					'&amount=' . $amount .
					'&currency=EUR' .
					'&paymentType=DB' .
					'&integrity=true';

		$data  = $this->getPaymentStatus($url, $data);
	}

	// MARK: release_milestone
	public function release_milestone(TransactionRequest $request):? RedirectResponse
	{
		try {
			$receiver_id = $request->receiver_id;
			$amount      = $request->safe()->__get('amount');
			$trans_query = DB::table('transactions')->where('id', $request->id);

			$trans = $trans_query->first();
			if (!$trans) {
				return to_route('transaction.index')->with('error', 'something went wrong');
			}

			DB::beginTransaction();

			$trans_query->update(['type' => 'release']);

			$proposal_query = DB::table('proposals')
					->where(['project_id' => $request->project_id, 'user_id' => $receiver_id]);

			$proposal = $proposal_query->first();
			if (!$proposal) {
				return to_route('transaction.index')->with('error', 'something went wrong');
			}

			$proposal_query->update(['finished' => 'finished']);

			DB::commit();

			$release  = true;
			$receiver = User::find($receiver_id);
			$user     = Auth::user();
			$view     = view('users.includes.notifications.milestone', compact('amount', 'release'))->render();

			$receiver->notify(new MilestoneNotification($amount, $user->name, $user->image, $view));
			Log::info('user released milestone');

			$this->forgetCache($receiver_id);

			return null;
		} catch (\Throwable) {
			DB::rollBack();
			abort(500, 'something went wrong');
		}
	}

	// MARK: post_funds
	public function post_funds(TransactionRequest $request): void
	{
		$data = $request->validated() +
			[
				'type'       => 'withdraw',
				'created_at' => now(),
				'owner_id'   => Auth::id(),
				'id'         => Str::uuid(),
			];

		DB::table('transactions')->insert($data);
	}
}
