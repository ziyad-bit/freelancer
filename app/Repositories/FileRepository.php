<?php

namespace App\Repositories;

use App\Interfaces\Repository\FileRepositoryInterface;
use App\Traits\{File, InsertAnyFile};
use Illuminate\Http\{Request};
use Illuminate\Support\Facades\{DB, Log, Storage};
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileRepository implements FileRepositoryInterface
{
	use File;

	// MARK: download_file
	public function download_file(string $file, string $type, string $dir):StreamedResponse
	{
		$path = $type . 's/' . $dir . '/';

		return Storage::download($path . $file);
	}

	// MARK: insertAnyFile
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

	// MARK: upload_file
	public function upload_file(Request $request):array
	{
		$dir       = $request->dir;
		$type      = $request->type;
		$path      = $type . 's/' . $dir;

		$file_name = $this->dropZoneUpload($request, $path, $type);

		return $file_name;
	}

	// MARK: destroy_file
	public function destroy_file(string $file, string $type, string $dir):bool
	{
		$path = $type . 's/' . $dir . '/';

		$storage_file = Storage::has($path . $file);
		$file_query   = DB::table('project_files')->where('file', $file);
		$db_file      = $file_query->first();

		if (!$storage_file || !$db_file) {
			return false;
		}

		Storage::delete($path . $file);

		$file_query->delete();

		return true;
	}
}
