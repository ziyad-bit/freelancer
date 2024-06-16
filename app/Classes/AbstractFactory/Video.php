<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};

class Video implements FileInterface
{
	####################################   insert   #####################################
	public function insert(Request $request, int $project_id):void
	{
		static $insert_called = false;

		if (!$insert_called) {
			$insert_called = true;

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
	}



	####################################    destroy   #####################################
	public function destroy(string $video):JsonResponse
	{
		$storage_video = Storage::has('video/' . $video);
		$db_video      = DB::table('project_files')->where('video', $video)->first();

		if ($storage_video && $db_video) {
			Storage::delete('video/' . $video);

			DB::table('project_files')->where('video', $video)->delete();

			return response()->json(['success' => 'you deleted successfully video']);
		}

		return response()->json(['error' => 'not found'], 404);
	}
}
