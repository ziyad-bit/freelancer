<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ProfileRepositoryInterface
{
	public function getUserInfo(Request $request):array;
	public function storeUserInfo(ProfileRequest $request):void;
	public function updateUserInfo(ProfileRequest $request):void;
	public function deleteUserInfo(Request $request):void;
}
