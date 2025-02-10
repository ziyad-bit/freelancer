<?php

namespace App\Traits;

use Illuminate\Support\Facades\{DB};
use Illuminate\Support\Str;

trait Slug
{
	//MARK: forgetCache
	public function createSlug(string $table, string $column, string $value):string
	{
		$count = DB::table($table)->where($column, $value)->count() + 1;

		return Str::slug($value) . '-' . $count;
	}
}
