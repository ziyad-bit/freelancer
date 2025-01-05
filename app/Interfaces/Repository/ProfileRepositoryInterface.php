<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

interface ProfileRepositoryInterface
{
	public function getUserInfo(string $slug):array;
	public function storeUserInfo(ProfileRequest $request):void;
	public function editUserInfo():?object;
	public function updateUserInfo(ProfileRequest $request):void;
	public function deleteUserInfo(Request $request):void;
}
