<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\DropzoneRequest;
use App\Interfaces\Repository\FileRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
	private $fileRepository;

	public function __construct(FileRepositoryInterface $fileRepository)
	{
		$this->middleware('auth');

		$this->fileRepository = $fileRepository;
	}

	// upload    #####################################
	public function upload(DropzoneRequest $request):JsonResponse
	{
		$file_name = $this->fileRepository->upload_file($request, 'projects/');

		return response()->json(['file_name' => $file_name['file_name'], 'original_name' => $file_name['original_name']]);
	}

	// download   #####################################
	public function download(string $file):StreamedResponse
	{
		return $this->fileRepository->download_file($file, 'projects/');
	}

	// destroy   #####################################
	public function destroy(string $file):JsonResponse
	{
		return $this->fileRepository->destroy_file($file, 'projects/');
	}
}
