<?php

namespace App\Traits;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

	//MARK: update
	public function updateImage(object $request, int $width = null, string $old_image, string $path = 'users', int $height = null):string
	{
		Storage::delete('image/' . $path . '/' . $old_image);

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
