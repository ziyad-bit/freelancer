<?php

namespace App\Classes\AbstractFactory;

use App\Interfaces\AbstractFactory\FileInterface;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};

class ApplicationFile implements FileInterface
{
	####################################   insert   #####################################
	public function insert(Request $request, int $project_id):void
	{
		static $insert_called = false;

		if (!$insert_called) {
			$insert_called = true;

			$files_arr     = [];
			$files         = $request->input('applications');

			if ($files != []) {
				foreach ($files as $file) {
					$files_arr[] = [
						'application' => $file,
						'project_id'  => $project_id,
						'created_at'  => now(),
					];
				}

				DB::table('project_files')->insert($files_arr);
			}
		}
	}
}
