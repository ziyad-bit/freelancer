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
		$this->middleware(['auth', 'verifyEmail'])->except('fetch');
		$this->middleware('project')->only(['destroy', 'edit', 'update']);
	}

	//MARK: fetch
	public function fetch(SearchRequest $request):View|JsonResponse
	{
		$data = $this->ProjectRepository->fetchProjects($request);

		if (request()->ajax()) {
			return response()->json($data);
		}

		return view('users.project.index', $data);
	}

	//MARK: create
	public function create():View
	{
		return view('users.project.create');
	}

	//MARK: store
	public function store(
		ProjectRequest $request,
		FileRepositoryInterface $fileRepository,
		SkillRepositoryInterface $skillRepository
	):RedirectResponse {
		$this->ProjectRepository->storeProject($request, $fileRepository, $skillRepository);

		return redirect()->back()->with('success', 'you added project successfully');
	}

	//MARK: show
	public function show(string $slug):View
	{
		$data = $this->ProjectRepository->showProject($slug);

		return view('users.project.show', $data);
	}

	//MARK: edit
	public function edit(SkillRepositoryInterface $skillRepository, string $slug):View|RedirectResponse
	{
		$project = $this->ProjectRepository->editProject($slug);

		return view('users.project.edit', compact('project'));
	}

	//MARK: update
	public function update(
		ProjectRequest $request,
		FileRepositoryInterface $fileRepository,
		SkillRepositoryInterface $skillRepository,
		string $slug
	):RedirectResponse {
		$this->ProjectRepository->updateProject($request, $fileRepository, $skillRepository, $slug);

		return redirect()->back()->with('success', 'you updated the project successfully');
	}

	//MARK: destroy
	public function destroy(string $slug):RedirectResponse
	{
		$this->ProjectRepository->deleteProject($slug);

		return to_route('home')->with('success', 'you deleted project successfully');
	}
}
