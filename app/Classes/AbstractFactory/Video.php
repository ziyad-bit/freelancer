<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Video implements FileInterface
{
	####################################   insert   #####################################
	public function insert(Request $request, int $project_id):void
	{
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

	####################################    download   #####################################
	public function download(string $file): StreamedResponse
	{
		return Storage::download('videos/' . $file);
	}

	####################################    destroy   #####################################
	public function destroy(string $video):JsonResponse
	{
		$storage_video = Storage::has('videos/' . $video);
		$db_video      = DB::table('project_files')->where('video', $video)->first();

		if ($storage_video && $db_video) {
			Storage::delete('videos/' . $video);

			DB::table('project_files')->where('video', $video)->delete();

			return response()->json(['success' => 'you deleted successfully video']);
		}

		return response()->json(['error' => 'not found'], 404);
	}
}
