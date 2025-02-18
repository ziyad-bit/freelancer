<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\Admins\DebateRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DebatesController extends Controller
{
	public function __construct(private DebateRepositoryInterface $debateRepository)
	{
		$this->middleware('auth:admins');
	}

    // MARK: index   
	public function index():View
	{
		$debates=$this->debateRepository->indexDebate();

		return view('admins.debate.index',compact('debates'));
	}

    // MARK: create   
	public function create():View
	{
		return view('');
	}

    // MARK: store   
    public function store( $request):RedirectResponse
	{
		return to_route('');
	}

    // MARK: show   
	public function show(int $id):View
	{
		$debate=$this->debateRepository->showDebate($id);

		return view('admins.debate.show',compact('debate'));
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
