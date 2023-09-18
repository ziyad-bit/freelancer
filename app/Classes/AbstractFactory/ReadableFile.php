<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReadableFile implements FileInterface
{
	####################################   insert   #####################################
	public function insert(Request $request, int $project_id):void
	{
		$files_arr = [];
		$files     = $request->input('files');

		if ($files != []) {
			foreach ($files as $file) {
				$files_arr[] = [
					'file'       => $file,
					'project_id' => $project_id,
					'created_at' => now(),
				];
			}

			DB::table('project_files')->insert($files_arr);
		}
	}

	####################################    download   #####################################
	public function download(string $file): StreamedResponse
	{
		return Storage::download('files/' . $file);
	}

	####################################    destroy   #####################################
	public function destroy(string $file):JsonResponse
	{
		$storage_file = Storage::has('files/' . $file);
		$db_file      = DB::table('project_files')->where('file', $file)->first();

		if ($storage_file && $db_file) {
			Storage::delete('files/' . $file);

			DB::table('project_files')->where('file', $file)->delete();

			return response()->json(['success' => 'you deleted successfully file']);
		}

		return response()->json(['error' => 'not found'], 404);
	}
}
