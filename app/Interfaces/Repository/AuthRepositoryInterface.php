<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
	public function login(UserRequest $request):RedirectResponse;
	public function storeUser(UserRequest $request):int;
	public function logoutUser(Request $request):void;
}
