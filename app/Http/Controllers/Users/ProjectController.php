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
		$response = $this->ProjectRepository->fetchProjects($request);

		if (!is_array($response)) {
			return $response;
		}

		return view('users.project.index')
			->with([
				'projects'    => $response['projects'],
				'searchTitle' => $response['searchTitle'],
				'cursor'      => $response['cursor'],
			]);
	}

	//MARK: create
	public function create(SkillRepositoryInterface $skillRepository):View
	{
		$skills  = $skillRepository->getSkills();

		return view('users.project.create', compact('skills'));
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
		$response = $this->ProjectRepository->showProject($id);

		if (!is_array($response)) {
			return $response;
		}

		return view('users.project.show')
			->with([
				'project'   => $response['project'],
				'proposals' => $response['proposals'],
				'cursor'    => $response['cursor'],
			]);
	}

	//MARK: edit
	public function edit(int $id, SkillRepositoryInterface $skillRepository):View|RedirectResponse
	{
		$skills  = $skillRepository->getSkills();

		$project_or_res =  $this->ProjectRepository->editProject($id, $skills);

		if ($project_or_res instanceof RedirectResponse) {
			return $project_or_res;
		}

		return view('users.project.edit')
			->with(['project' => $project_or_res, 'skills' => $skills]);
	}

	//MARK: update
	public function update(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$response = $this->ProjectRepository->updateProject($request, $id, $fileRepository, $skillRepository);

		if ($response instanceof RedirectResponse) {
			return $response;
		}

		return redirect()->back()->with('success', 'you updated the project successfully');
	}

	//MARK: destroy
	public function destroy(int $id):RedirectResponse
	{
		$response = $this->ProjectRepository->deleteProject($id);

		if ($response instanceof RedirectResponse) {
			return $response;
		}

		return to_route('project.fetch')->with('success', 'you deleted project successfully');
	}
}
