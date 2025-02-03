<?php

namespace App\Traits;

trait GetCursor
{
	public function getCursor($data):bool|string
	{
		if ($data->hasMorePages()) {
			return  $data->nextCursor()->encode();
		}

		return false;
	}
}
