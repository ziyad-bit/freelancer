<?php

namespace App\Repositories;

use App\Classes\AbstractFactory\FileAbstractFactory;
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Traits\InsertAnyFile;
use App\Traits\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileRepository implements FileRepositoryInterface
{
	use UploadFile;

	####################################   download_file   #####################################
	public function download_file(string $file):StreamedResponse
	{
		$type  = 'create_' . substr($file, 0, 5);

		return (new FileAbstractFactory())->$type()->download($file);
	}

	####################################   insertAnyFile   #####################################
	public function insertAnyFile(Request $request, int $project_id):void
	{
		$fileFactory = new FileAbstractFactory();

		$fileFactory->create_image()->insert($request, $project_id);
		$fileFactory->create_files()->insert($request, $project_id);
		$fileFactory->create_video()->insert($request, $project_id);
	}

	####################################   insertAnyFile   #####################################
	public function upload_file(Request $request):array
	{
		if ($request->has('image')) {
			$file_name = $this->dropZoneUpload($request, 'images/projects', 'image');
		}

		if ($request->has('files')) {
			$file_name = $this->dropZoneUpload($request, 'files/', 'files');
		}

		if ($request->has('video')) {
			$file_name = $this->dropZoneUpload($request, 'videos/', 'video');
		}

		return $file_name;
	}

	####################################   destroy_file   #####################################
	public function destroy_file(string $file):JsonResponse
	{
		$type  = 'create_' . substr($file, 0, 5);

		return (new FileAbstractFactory())->$type()->destroy($file);
	}
}
