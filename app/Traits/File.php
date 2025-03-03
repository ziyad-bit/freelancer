<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait File
{
	//MARK: uploadFile
	public function upload(object $request, string $path, string $type):string
	{
		$file     = $request->file($type);
		$fileName = $file->hashName();

		Storage::putFileAs($path, $file, $fileName);

		return $fileName;
	}

	//MARK: uploadAndResize
	public function uploadAndResize(object $request, int $width = 0, string $path, string $input_name = 'image', int $height = 0):string
	{

		$file = $request->file($input_name);
		$name = $file->hashName();

		$width  = $width == 0 ? null : $width;
		$height = $height == null ? null : $height;

		$img = Image::make($file)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		})->encode();

		Storage::put('images/' . $path . '/' . $name, $img);

		return $name;
	}

	//MARK: update
	public function updateImage(object $request, int $width = 0, string $old_image, string $path = 'users', int $height = 0):string
	{
		Storage::delete('images/' . $path . '/' . $old_image);

		$image = $this->uploadAndResize($request, $width, $path,'image' ,$height);

		return $image;
	}

	//MARK: dropZoneUpload
	public function dropZoneUpload(Request $request, string $path, string $type):array
	{
		$file_name      = $this->upload($request, $path, $type);
		$original_name  = $request->file($type)->getClientOriginalName();

		return ['file_name' => $file_name, 'original_name' => $original_name];
	}
}
