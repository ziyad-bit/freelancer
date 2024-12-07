<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\{RedirectResponse, Request};

interface AuthRepositoryInterface
{
	public function login(LoginRequest $request):RedirectResponse;
	public function storeUser(SignupRequest $request):void;
	public function logoutUser(Request $request):void;
}
