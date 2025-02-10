<?php

namespace App\Http\Controllers\Users;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\DebateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\GeneralNotFoundException;
use App\Exceptions\RecordExistException;

class MyProjectController extends Controller
{
	// MARK: index
	public function index():View
	{
		$auth_id  = Auth::id();
		$projects = DB::table('projects')
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
				->where('projects.user_id',$auth_id)
				->where('type','!=','withdraw')
				->orWhere(fn($query) => $query->where(['proposals.user_id'=>$auth_id,'proposals.approval'=>'approved']))
				->get();

		return view('users.myProject.index', compact('projects'));
	}

	// MARK: create
	public function debate_create(int $project_id,int $user_id):View
	{
		return view('users.myProject.debate_create', compact('project_id','user_id'));
	}

	// MARK: store
	public function debate_store(DebateRequest $request):RedirectResponse
	{
		$transaction_id = DB::table('transactions')
				->where('project_id',$request->project_id)
				->value('id');

		if (!$transaction_id) {
			throw new GeneralNotFoundException('transaction');
		}

		$debate = DB::table('debates')
				->where('transaction_id',$transaction_id)
				->value('id');
		
		if ($debate) {
			throw new RecordExistException('debate');
		}

		$data = $request->validated() + [
			'transaction_id' => $transaction_id,
			'owner_id'       => Auth::id(),
			'created_at'     => now(),
		];

		DB::table('debates')->insert($data);

		return to_route('my-project.index')->with('success','record is created successfully');
	}
}
