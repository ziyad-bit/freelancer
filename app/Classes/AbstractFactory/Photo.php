<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Intervention\Image\Facades\Image;

class Photo implements FileInterface
{
	// uploadAndResize    #####################################
	public function uploadAndResize(object $request, int $width = null, string $path, int $height = null):string
	{
		$file = $request->file('image');
		$name = $file->hashName();

		$img = Image::make($file)->resize(
			$width,
			$height,
			function ($constraint) {
				$constraint->aspectRatio();
			}
		)->encode();

		Storage::put('images/' . $path . '/' . $name, $img);

		return $name;
	}

	// insert   #####################################
	public function insert(Request $request, string $table_name, string $column_name, int $column_value, string $file):void
	{
		static $images_arr    = [];

		$images_arr[] = [
			'image'      => $file,
			$column_name => $column_value,
			'created_at' => now(),
		];

		if (count($images_arr) == $request->all_images_count) {
			DB::table($table_name)->insert($images_arr);
		}
	}

	// update   #####################################
	public function update(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('image/' . $path . '/' . $old_image);

		$image = $this->uploadAndResize($request, $width, $path, $height);

		return $image;
	}
}
