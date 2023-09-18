<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
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
	public function create(SkillRepositoryInterface $skillRepository):View
	{
		$skills = $skillRepository->getSkills();

		return view('users.project.create', compact('skills'));
	}

	####################################   store   #####################################
	public function store(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$this->ProjectRepository->storeProject($request, $fileRepository, $skillRepository);

		return redirect()->back()->with('success', 'you added successfully project');
	}

	####################################   show   #####################################
	public function show(int $id):View|RedirectResponse
	{
		return $this->ProjectRepository->showProject($id);
	}

	####################################   edit   #####################################
	public function edit(int $id, SkillRepositoryInterface $skillRepository):View|RedirectResponse
	{
		$skills  = $skillRepository->getSkills();

		return  $this->ProjectRepository->editProject($id, $skills);
	}

	####################################   update   #####################################
	public function update(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		return $this->ProjectRepository->updateProject($request, $id, $fileRepository, $skillRepository);
	}

	####################################   destroy   #####################################
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
