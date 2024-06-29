<?php

namespace App\Traits;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};
use Symfony\Component\HttpFoundation\StreamedResponse;

trait File
{
	// uploadFile    #####################################
	public function upload(object $request, string $path, string $type):string
	{
		$file     = $request->file('file');
		$fileName = $file->hashName();

		Storage::putFileAs($path, $file, $type . '-' . $fileName);

		return $fileName;
	}

	// dropZoneUpload   #####################################
	public function dropZoneUpload(Request $request, string $path, string $type):array
	{
		$file_name          = $this->upload($request, $path, $type);
		$original_name      = $request->file('file')->getClientOriginalName();

		return ['file_name' => $file_name, 'original_name' => $original_name];
	}

	// download   #####################################
	public function download(string $file, string $path): StreamedResponse
	{
		return Storage::download($path . $file);
	}

	// destroy   #####################################
	public function destroy(string $file, string $type, $dir):JsonResponse
	{
		$path = $type . 's/' . $dir;

		$storage_file = Storage::has($path . $file);
		$db_file      = DB::table('project_files')->where($type, $file)->first();

		if ($storage_file && $db_file) {
			Storage::delete($path . $file);

			DB::table('project_files')->where($type, $file)->delete();

			return response()->json(['success' => 'you deleted successfully ' . $type]);
		}

		return response()->json(['error' => 'not found'], 404);
	}
}
