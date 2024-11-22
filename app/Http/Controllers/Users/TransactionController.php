<?php

namespace App\Http\Controllers\Users;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionControllerRequest;
use App\Interfaces\Repository\TransactionRepositoryInterface;

class TransactionController extends Controller
{
	public function __construct(private TransactionRepositoryInterface $transactionRepository)
	{
		$this->middleware('auth');
	}
    // MARK: index   
	public function index():View
	{
		return view('');
	}

    // MARK: create   
	public function create(int $project_id,int $receiver_id):View
	{
		return $this->transactionRepository->create_milestone($project_id,$receiver_id);
	}

    // MARK: store   
    public function store(TransactionRequest $request):RedirectResponse
	{
		$this->transactionRepository->store_milestone($request);

		return to_route('project.show',$request->project_id)->with(['success'=>'milestone is created successfully']);
	}

    // MARK: show   
	public function show(int $id):View
	{
		return view('');
	}

    // MARK: edit   
	public function edit(int $id):View
	{
		return view('');
	}

    // MARK: update   
	public function update( $request , int $id):RedirectResponse
	{
		return to_route('');
	}

    // MARK: destroy   
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
