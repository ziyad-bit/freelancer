<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};

class Video implements FileInterface
{
	// insert   #####################################
	public function insert(Request $request,string $table_name ,string $column_name , int $column_value,string $file):void
	{
		static $videos_arr = [];

		$videos_arr[] = [
			'video'      => $file,
			$column_name => $column_value,
			'created_at' => now(),
		];

		if (count($videos_arr) == $request->all_videos_count) {
			DB::table($table_name)->insert($videos_arr);
		}
	}



	// destroy   #####################################
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
