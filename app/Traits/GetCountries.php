<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait GetCountries
{
	####################################   getCountries   #####################################
	public function getCountries(): array
	{
		$response = Http::get('https://restcountries.com/v3.1/all?fields=name');

		return $response->collect()->pluck('name.common')->flatten()->sort()->toArray();
	}
}
