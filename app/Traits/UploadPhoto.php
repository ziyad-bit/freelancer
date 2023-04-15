<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait UploadPhoto
{
	public function uploadPhoto(object $request, int $width = null, string $path = 'users', int $height = null):string
	{
		$file = $request->file('image');
		$name = $file->hashName();

		$img = Image::make($file)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		})->encode();

		Storage::put('images/' . $path . '/' . $name, $img);

		return $name;
	}

	public function updatePhoto(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('images/' . $path . '/' . $old_image);

		$image = $this->uploadPhoto($request, $width, $path, $height);

		return $image;
	}
}
