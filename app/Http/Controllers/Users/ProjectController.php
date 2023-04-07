<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
	####################################   index   #####################################
	public function index():View
	{
		return view('');
	}

	####################################   create   #####################################
	public function create():View
	{
		return view('');
	}

	####################################   store   #####################################
	public function store($request):RedirectResponse
	{
		return to_route('');
	}

	####################################   show   #####################################
	public function show(int $id):View
	{
		return view('');
	}

	####################################   edit   #####################################
	public function edit(int $id):View
	{
		return view('');
	}

	####################################   update   #####################################
	public function update($request, int $id):RedirectResponse
	{
		return to_route('');
	}

	####################################   destroy   #####################################
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
