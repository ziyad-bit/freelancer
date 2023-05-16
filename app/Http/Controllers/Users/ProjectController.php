<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
	private $ProjectRepository;

	public function __construct(ProjectRepositoryInterface $ProjectRepository)
	{
		$this->middleware('auth');

		$this->ProjectRepository = $ProjectRepository;
	}
	####################################   index   #####################################
	public function index_projects(Request $request):View|JsonResponse
	{
		return $this->ProjectRepository->getProjects($request);
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
		$project=$this->ProjectRepository->showProject($id);

		return view('users.project.show');
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
