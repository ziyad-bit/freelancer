<?php

namespace App\Http\Controllers\Users;

use Illuminate\View\View;
use App\Traits\UploadFile;
use App\Traits\UploadPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DropzoneRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Interfaces\Repository\ProjectRepositoryInterface;

class ProjectController extends Controller
{
	use UploadPhoto;
	use UploadFile;

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
		return view('users.project.create');
	}

	####################################   store   #####################################
	public function store(ProjectRequest $request):RedirectResponse
	{
		$this->ProjectRepository->storeProject($request);

		return to_route('project.create')->with('success', 'you added successfully project');
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
	public function show(int $id):View
	{
		$project = $this->ProjectRepository->showProject($id);

		return view('users.project.show', compact('project'));
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
