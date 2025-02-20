<?php

namespace App\Repositories;

use App\Exceptions\RecordExistException;
use App\Http\Requests\DebateRequest;
use App\Interfaces\Repository\AuthProjectRepositoryInterface;
use App\Traits\DatabaseCache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB};

class AuthProjectRepository implements AuthProjectRepositoryInterface
{
	use DatabaseCache;

	// MARK: index Projects
	public function indexAuthProjects():Collection
	{
		$auth_id  = Auth::id();

		return DB::table('projects')
				->select(
					'project_owner.name as client_name',
					'proposal_owner.name as freelancer_name',
					'projects.title',
					'projects.created_at as date',
					'projects.user_id as client_id',
					'projects.id as project_id',
					'proposals.user_id as freelancer_id',
					'transactions.id as transaction_id',
					'type',
					'amount',
					'debates.owner_id as debate_owner_id',
				)
				->join('users as project_owner', 'projects.user_id', '=', 'project_owner.id')
				->leftJoin('proposals', 'projects.id', '=', 'proposals.project_id')
				->leftJoin('users as proposal_owner', 'proposals.user_id', '=', 'proposal_owner.id')
				->leftJoin('transactions', 'projects.id', '=', 'transactions.project_id')
				->leftJoin('debates', 'transactions.id', '=', 'debates.transaction_id')
				->where('projects.user_id', $auth_id)
				->where('type', '!=', 'withdraw')
				->orWhere(fn ($query) => $query->where(['proposals.user_id' => $auth_id, 'proposals.approval' => 'approved']))
				->get();
	}

	// MARK: store Debate
	public function storeDebateData(DebateRequest $request): void
	{
		$transaction_id = DB::table('transactions')
				->where('project_id', $request->project_id)
				->value('id');

		$debate = DB::table('debates')
				->where('transaction_id', $transaction_id)
				->value('id');

		if ($debate) {
			throw new RecordExistException('debate');
		}

		$data = $request->validated() + [
			'transaction_id'     => $transaction_id,
			'initiator_id'       => Auth::id(),
			'created_at'         => now(),
		];

		DB::table('debates')->insert($data);
	}
}
