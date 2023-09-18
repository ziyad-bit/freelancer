<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function PHPSTORM_META\type;

trait UploadFile
{
	####################################     uploadFile    #####################################
	public function uploadFile(object $request, string $path,  string $type):string
	{
		$file     = $request->file($type);
		$fileName = $file->hashName();

		Storage::putFileAs($path, $file, $type . $fileName);

		return $fileName;
	}

	####################################    dropZoneUpload   #####################################
	public function dropZoneUpload(Request $request, string $path,string $type):array
	{
		$file_name          = $this->uploadFile($request, $path, $type);
		$original_name = $request->file($type)->getClientOriginalName();


		return ['file_name' => $file_name, 'original_name' => $original_name];
	}

	
}
