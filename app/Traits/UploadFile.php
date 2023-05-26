<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFile
{
	use UploadPhoto;

	public function uploadFile(object $request, string $path, string $input_name):string
	{
		$file     = $request->file($input_name);
		$fileName = $file->hashName();

		Storage::putFileAs($path, $file, $fileName);

		return $fileName;
	}

	public function uploadAnyFile($request)
	{
		if ($request->has('image')) {
			$file          = $this->uploadFile($request, 'images/projects', 'image');
			$original_name = $request->file('image')->getClientOriginalName();
		}

		if ($request->has('file')) {
			$file          = $this->uploadFile($request, 'files', 'file');
			$original_name = $request->file('file')->getClientOriginalName();
		}

		if ($request->has('video')) {
			$file          = $this->uploadFile($request, 'videos', 'video');
			$original_name = $request->file('video')->getClientOriginalName();
		}

		return ['file_name' => $file, 'original_name' => $original_name];
	}
}
