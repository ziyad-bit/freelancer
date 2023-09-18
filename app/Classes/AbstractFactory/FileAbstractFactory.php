<?php

namespace App\Classes\AbstractFactory;

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
