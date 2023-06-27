<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\DropzoneRequest;
use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\ProjectRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use App\Traits\UploadFile;
use App\Traits\UploadPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectController extends Controller
{
	use UploadPhoto;
	use UploadFile;

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

	####################################   upload_images   #####################################
	public function upload_files(DropzoneRequest $request):JsonResponse
	{
		$file_names = $this->uploadAnyFile($request);

		return response()->json(['file_name' => $file_names['file_name'], 'original_name' => $file_names['original_name']]);
	}

	####################################   upload_images   #####################################
	public function download_file(string $file):StreamedResponse
	{
		return $this->ProjectRepository->download_file($file);
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

		return view('users.project.edit',compact('project'));
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
