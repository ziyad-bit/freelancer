<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\DropzoneRequest;
use App\Interfaces\Repository\FileRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
	public function __construct(private FileRepositoryInterface $fileRepository)
	{
		$this->middleware('auth');
	}

	//MARK: upload
	public function upload(DropzoneRequest $request):JsonResponse
	{
		$file_name = $this->fileRepository->upload_file($request);

		return response()->json(['file_name' => $file_name['file_name'], 'original_name' => $file_name['original_name']]);
	}

	//MARK: download
	public function download(string $file):StreamedResponse
	{
		return $this->fileRepository->download_file($file, 'projects/');
	}

	//MARK: destroy
	public function destroy(string $file):JsonResponse
	{
		return $this->fileRepository->destroy_file($file, 'projects/');
	}
}
