<?php

namespace App\Interfaces\AbstractFactory;

use Illuminate\Http\Request;

interface FileInterface
{
	public function insert(Request $request, int $project_id):void;
}
