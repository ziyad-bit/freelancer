<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\Admins\ProjectRepositoryInterface;
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectsController extends Controller
{
	public function __construct(private ProjectRepositoryInterface $projectRepository)
	{
		$this->middleware('auth:admins');
	}
	// MARK: index
	public function index():View
	{
		$projects = $this->projectRepository->indexProject();

		return view('admins.project.index', compact('projects'));
	}

	// MARK: create
	public function create():View
	{
		return view('admins.project.create');
	}

	// MARK: store
	public function store(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse
	{
		$this->projectRepository->storeProject($request, $fileRepository, $skillRepository);

		return to_route('admin.project.create')->with('success', 'project is created successfully');
	}

	// MARK: show
	public function show(string $slug):View
	{
		$data = $this->projectRepository->showProject($slug);

		return view('admins.project.show', $data);
	}

	// MARK: active
	public function active(int $id):RedirectResponse
	{
		$this->projectRepository->activeProject($id);
	
		return to_route('admin.project.index')->with('success','you activated the project successfully');
	}

	// MARK: edit
	public function edit(string $slug):View
	{
		$project=$this->projectRepository->editProject($slug);

		return view('admins.project.edit',compact('project'));
	}

	// MARK: update
	public function update(ProjectRequest $request,FileRepositoryInterface $fileRepository,SkillRepositoryInterface $skillRepository,string $slug):RedirectResponse
	{
		$this->projectRepository->updateProject($request,$fileRepository,$skillRepository,$slug);

		return to_route('admin.project.edit',$slug)->with('success','you updated project successfully');
	}

	// MARK: destroy
	public function destroy(string $slug):RedirectResponse
	{
		$this->projectRepository->deleteProject($slug);

		return to_route('admin.project.index')->with('success','you deleted the project successfully');
	}
}
