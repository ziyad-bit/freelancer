<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use App\Traits\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Image implements FileInterface
{
	####################################     uploadAndResize    #####################################
	public function uploadAndResize(object $request, int $width = null, string $path, int $height = null):string
	{
		$file = $request->file('image');
		$name = $file->hashName();

		$img = Image::make($file)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		})->encode();

		Storage::put('images/' . $path . '/' . $name, $img);

		return $name;
	}

	####################################   insert   #####################################
	public function insert(Request $request, int $project_id):void
	{
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
	}

	####################################    update   #####################################
	public function download( string $file ):StreamedResponse
	{
		return Storage::download('images/projects/' . $file);
	}

	####################################    update   #####################################
	public function update(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('images/' . $path . '/' . $old_image);

		$image = $this->uploadAndResize($request, $width, $path, $height);

		return $image;
	}

	####################################    destroy   #####################################
	public function destroy(string $file):JsonResponse
	{
		$storage_file = Storage::has('images/projects/' . $file);
		$db_file      = DB::table('project_files')->where('image', $file)->first();

		if ($storage_file && $db_file) {
			Storage::delete('images/projects/' . $file);

			DB::table('project_files')->where('image', $file)->delete();

			return response()->json(['success' => 'you deleted successfully image']);
		}

		return response()->json(['error' => 'not found'], 404);
	}
}
