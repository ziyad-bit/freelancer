<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationFile implements FileInterface
{
	// insert   #####################################
	public function insert(Request $request,string $table_name ,string $column_name , int $column_value,string $file):void
	{
		static $files_arr     = [];

		$files_arr[] = [
			'file' => $file,
			$column_name  => $column_value,
			'created_at'  => now(),
		];

		if (count($files_arr) == $request->all_apps_count) {
			DB::table($table_name)->insert($files_arr);
		}
	}
}
