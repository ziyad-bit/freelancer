<?php

namespace App\Interfaces\AbstractFactory;

use Illuminate\Http\Request;

interface FileInterface
{
	public function insert(
		Request $request,
		string $table_name,
		string $column_name,
		int $column_value,
		string $file,
		string $type,
	):void;
}
