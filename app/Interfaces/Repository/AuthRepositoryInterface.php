<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{LoginRequest, SignupRequest, SmsVerificationRequest};
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
	public function login(LoginRequest $request):?string;
	public function storeUser(SignupRequest $request):array;
	public function smsVerification(SmsVerificationRequest $request):array;
	public function logoutUser(Request $request):void;
}
