<?php

namespace App\Repositories;

use App\Interfaces\Repository\FileRepositoryInterface;
use App\Traits\{File, InsertAnyFile};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\DB;
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
	public function insert_file(Request $request, string $table_name, string $column_name, int $column_value):array
	{
		if ($request->has('files')) {
			$files  = $request->input('files');

			$files_arr = [];
			if ($files != []) {
				for ($i = 1; $i < count($files) + 1; $i++) {
					$type = $files[$i]['type'];
					$name = $files[$i]['name'];

					$files_arr[] = [
						'file'       => $name,
						'type'       => $type,
						$column_name => $column_value,
						'created_at' => now(),
					];
				}

				DB::table($table_name)->insert($files_arr);
			}

			return $files;
		}

		return [];
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
