<?php

namespace App\Repositories;

use App\Interfaces\Repository\FileRepositoryInterface;
use App\Traits\InsertAnyFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileRepository implements FileRepositoryInterface
{
	####################################   download_file   #####################################
	public function download_file(string $file):StreamedResponse
	{
		$type  = substr($file, 0, 5);

		if ($type === 'image') {
			return Storage::download('images/projects/' . $file);
		}

		if ($type === 'files') {
			return Storage::download('files/' . $file);
		}

		if ($type === 'video') {
			return Storage::download('videos/' . $file);
		}
	}

	####################################   insertAnyFile   #####################################
	public function insertAnyFile(Request $request, int $project_id):void
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

		$images_arr = [];
		$images     = $request->input('images');

		if ($images != []) {
			foreach ($images as $image) {
				$images_arr[] = [
					'image'      => $image,
					'project_id' => $project_id,
					'created_at' => now(),
				];
			}

			DB::table('project_files')->insert($images_arr);
		}

		$videos_arr = [];
		$videos     = $request->input('videos');

		if ($videos != []) {
			foreach ($videos as $video) {
				$videos_arr[] = [
					'video'      => $video,
					'project_id' => $project_id,
					'created_at' => now(),
				];
			}

			DB::table('project_files')->insert($videos_arr);
		}
	}

	####################################   destroy_file   #####################################
	public function destroy_file(string $file):JsonResponse
	{
		$type  = substr($file, 0, 5);

		if ($type === 'image') {
			if (Storage::has('images/projects/' . $file)) {
				Storage::delete('images/projects/' . $file);

				$db_file = DB::table('project_files')->where('image', $file)->first();

				if ($db_file) {
					DB::table('project_files')->where('image', $file)->delete();
				} else {
					return response()->json(['error' => 'not found'], 404);
				}

				return response()->json(['success' => 'you deleted successfully file']);
			}

			return response()->json(['error' => 'not found'], 404);
		}

		if ($type === 'files') {
			if (Storage::has('files/' . $file)) {
				Storage::delete('files/' . $file);

				$db_file = DB::table('project_files')->where('file', $file)->first();

				if ($db_file) {
					DB::table('project_files')->where('file', $file)->delete();
				} else {
					return response()->json(['error' => 'not found'], 404);
				}

				return response()->json(['success' => 'you deleted successfully file']);
			}

			return response()->json(['error' => 'not found'], 404);
		}

		if ($type === 'video') {
			if (Storage::has('video/' . $file)) {
				Storage::delete('video/' . $file);

				$db_file = DB::table('project_files')->where('video', $file)->first();

				if ($db_file) {
					DB::table('project_files')->where('video', $file)->delete();
				} else {
					return response()->json(['error' => 'not found'], 404);
				}

				return response()->json(['success' => 'you deleted successfully file']);
			}

			return response()->json(['error' => 'not found'], 404);
		}
	}
}
