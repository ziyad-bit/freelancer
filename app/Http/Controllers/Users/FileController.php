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
		$this->middleware('file')->only('destroy');
	}

	//MARK: upload
	public function upload(DropzoneRequest $request):JsonResponse
	{
		$file_name = $this->fileRepository->upload_file($request);

		return response()->json(['file_name' => $file_name['file_name'], 'original_name' => $file_name['original_name']]);
	}

	//MARK: download
	public function download(string $file, string $type, string $dir):StreamedResponse
	{
		return $this->fileRepository->download_file($file, $type, $dir);
	}

	//MARK: destroy
	public function destroy(string $file, string $type, string $dir):JsonResponse
	{
		$this->fileRepository->destroy_file($file, $type, $dir);

		return response()->json(['success' => 'you deleted ' . $type . ' successfully']);
	}
}
