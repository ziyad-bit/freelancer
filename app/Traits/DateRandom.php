<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait DateRandom
{
	public function dateRandom()
	{
		$days   = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'];
		$months = ['01', '02', '03', '04', '05', '06', '07', '08'];

		return '2022-' . Arr::random($months) . '-' . Arr::random($days) . ' 01:01:01';
	}
}
