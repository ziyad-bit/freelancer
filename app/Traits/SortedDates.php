<?php

namespace App\Traits;

use Faker\Generator;

trait SortedDates
{
	public function getAscSortedDates(Generator $faker,int $number=300):array
	{
		// Define the date range for generating random dates.
		$startDate = now()->subDays($number);
		$endDate = now();
	
		$dates = [];
		for ($i=0; $i < $number; $i++) { 
			$dates[] = $faker->dateTimeBetween($startDate, $endDate);
		}
		
		// Sort the dates in ascending order (oldest date first).
		usort($dates, function ($a, $b) {
			return $a <=> $b;
		});

		return $dates;
	}
}
