<?php

namespace App\Classes\AbstractFactory;

use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Classes\AbstractFactory\Image;
use App\Classes\AbstractFactory\Video;
use Illuminate\Support\Facades\Storage;
use App\Classes\AbstractFactory\ReadableFile;
use App\Interfaces\AbstractFactory\FileInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileAbstractFactory 
{
	####################################    dropZoneUpload   #####################################
	public function create_files():ReadableFile
	{
		return new ReadableFile();
	}

	####################################     uploadAndResize    #####################################
	public function create_image():Image
	{
		return new Image();
	}

    
	####################################   insert   #####################################
	public function create_video():Video
	{
		return new Video();
	}
}
