<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Collection;

interface ProfileRepositoryInterface
{
	public function getUserSkills():Collection;
	public function getUserInfo():object|null;
	public function getCountries():array;
	public function storeUserInfo(ProfileRequest $request):void;
	public function updateUserInfo(ProfileRequest $request):void;
}
