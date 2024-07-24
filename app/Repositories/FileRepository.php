<?php

namespace App\Repositories;

use App\Classes\AbstractFactory\FileAbstractFactory;
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Traits\{File, InsertAnyFile};
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileRepository implements FileRepositoryInterface
{
	use File;

	// download_file   #####################################
	public function download_file(string $file, string $dir):StreamedResponse
	{
		$position  = strpos($file, '-');
		$type      = substr($file, 0, $position);
		$path      = $type . 's/' . $dir;

		return $this->download($file, $path);
	}

	// insertAnyFile   #####################################
	public function insertAnyFile(Request $request, int $project_id):void
	{
		$files       = $request->input('files');
		$fileFactory = new FileAbstractFactory();

		if ($files != []) {
			foreach ($files as  $file) {
				$position  = strpos($file, '-');
				$type      = 'create_' . substr($file, 0, $position);

				$fileFactory->$type()->insert($request, $project_id);
			}
		}
	}

	// upload_file   #####################################
	public function upload_file(Request $request):array
	{
		$dir       = $request->dir;
		$type      = $request->type;
		$path      = $type . 's/' . $dir;

		$file_name = $this->dropZoneUpload($request, $path, $type);

		return $file_name;
	}

	// destroy_file   #####################################
	public function destroy_file(string $file, string $dir):JsonResponse
	{
		$position  = strpos($file, '-');
		$type      = substr($file, 0, $position);

		return $this->destroy($file, $type, $dir);
	}
}
