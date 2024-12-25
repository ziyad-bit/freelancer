<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{LoginRequest, SignupRequest};
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
	public function login(LoginRequest $request):?string;
	public function storeUser(SignupRequest $request):void;
	public function logoutUser(Request $request):void;
}
