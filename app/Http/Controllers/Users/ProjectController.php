<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\{ProjectRequest, SearchRequest};
use App\Interfaces\Repository\{FileRepositoryInterface, ProjectRepositoryInterface, SkillRepositoryInterface};
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\View\View;

class ProjectController extends Controller
{
	public function __construct(private ProjectRepositoryInterface $ProjectRepository)
	{
		$this->middleware('auth');
		$this->middleware('project')->only(['destroy', 'edit', 'update']);
	}

	//MARK: fetch
	public function fetch(SearchRequest $request):View|JsonResponse
	{
		$data = $this->ProjectRepository->fetchProjects($request);

		if (request()->ajax()) {
			return response()->json($data);
		}

		return view('users.project.index',$data);
	}

	//MARK: create
	public function create():View
	{
		return view('users.project.create');
	}

	//MARK: store
	public function store(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$this->ProjectRepository->storeProject($request, $fileRepository, $skillRepository);

		return redirect()->back()->with('success', 'you added project successfully');
	}

	//MARK: show
	public function show(int $id):View|RedirectResponse|JsonResponse
	{
		$data = $this->ProjectRepository->showProject($id);

		if (request()->ajax()) {
			return response()->json($data);
		}

		return view('users.project.show', $data);
	}

	//MARK: edit
	public function edit(int $id, SkillRepositoryInterface $skillRepository):View|RedirectResponse
	{
		$skills  = $skillRepository->getSkills();
		$project = $this->ProjectRepository->editProject($id, $skills);

		return view('users.project.edit', compact('project', 'skills'));
	}

	//MARK: update
	public function update(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$this->ProjectRepository->updateProject($request, $id, $fileRepository, $skillRepository);

		return redirect()->back()->with('success', 'you updated the project successfully');
	}

	//MARK: destroy
	public function destroy(int $id):RedirectResponse
	{
		$this->ProjectRepository->deleteProject($id);

		return to_route('project.fetch')->with('success', 'you deleted project successfully');
	}
}
