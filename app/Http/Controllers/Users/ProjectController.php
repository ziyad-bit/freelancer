<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
	private $ProjectRepository;
	private $skillRepository;

	public function __construct(ProjectRepositoryInterface $ProjectRepository, SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$this->skillRepository   = $skillRepository;
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
		$skills = $this->skillRepository->getSkills();

		return view('users.project.create', compact('skills'));
	}

	####################################   store   #####################################
	public function store(ProjectRequest $request):RedirectResponse
	{
		$this->ProjectRepository->storeProject($request);

		return redirect()->back()->with('success', 'you added successfully project');
	}

	####################################   show   #####################################
	public function show(int $id):View|RedirectResponse
	{
		return $this->ProjectRepository->showProject($id);
	}

	####################################   edit   #####################################
	public function edit(int $id):View
	{
		$project = $this->ProjectRepository->editProject($id);
		$skills  = $this->skillRepository->getSkills();

		return view('users.project.edit', compact('project', 'skills'));
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
