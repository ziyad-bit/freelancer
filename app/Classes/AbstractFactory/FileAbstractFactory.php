<?php

namespace App\Classes\AbstractFactory;

class FileAbstractFactory
{
	####################################    dropZoneUpload   #####################################
	public function create_application():ApplicationFile
	{
		return app(ApplicationFile::class);
	}

	####################################     uploadAndResize    #####################################
	public function create_image():Image
	{
		return app(Image::class);
	}


	####################################   insert   #####################################
	public function create_video():Video
	{
		return app(Video::class);
	}
}
