<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;

interface ProfileRepositoryInterface
{
	public function getUserSkills():Collection;
	public function getUserInfo():object|null;
	public function getCountries():array;
}
