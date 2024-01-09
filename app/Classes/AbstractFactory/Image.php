<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};

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
		static $insert_called = false;

		if (!$insert_called) {
			$insert_called = true;

			$images_arr    = [];
			$images        = $request->input('images');

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
	}

	####################################    update   #####################################
	public function update(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('image/' . $path . '/' . $old_image);

		$image = $this->uploadAndResize($request, $width, $path, $height);

		return $image;
	}
}
