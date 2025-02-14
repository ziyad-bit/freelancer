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

		Storage::putFileAs('public/'.$path, $file, $fileName);

		return $fileName;
	}

	//MARK: uploadAndResize
	public function uploadAndResize(object $request, int $width = null, string $path,string $input_name='image', int $height = null):string
	{
		$file = $request->file($input_name);
		
		$name = $file->hashName();

		$img = Image::make($file)->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		})->encode();

		Storage::put('public/images/' . $path . '/' . $name, $img);

		return $name;
	}

	//MARK: update
	public function updateImage(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('public/images/' . $path . '/' . $old_image);

		$image = $this->uploadAndResize($request, $width, $path, $height);

		return $image;
	}

	//MARK: dropZoneUpload
	public function dropZoneUpload(Request $request, string $path, string $type):array
	{

		$file_name          = $this->upload($request, $path, $type);
		$original_name      = $request->file($type)->getClientOriginalName();

		return ['file_name' => $file_name, 'original_name' => $original_name];
	}
}
