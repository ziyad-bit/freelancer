<?php

namespace App\Http\Controllers\Users;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\{AuthRepositoryInterface};
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
	public function __construct(private AuthRepositoryInterface $authRepository)
	{
		$this->middleware('auth')->only('logout');
		$this->middleware('guest')->except('logout');
	}

	//MARK: create
	public function test():View
	{
		return view('users.auth.signup');
	}
	//MARK: create
	public function create():View
	{
		return view('users.auth.signup');
	}

	//MARK: store
	public function store(SignupRequest $request):RedirectResponse
	{
		$this->authRepository->storeUser($request);

		return to_route('home');
	}

	//MARK: getVerify
	public function getVerify():View
	{
		return view('users.auth.verify-email');
	}

	//MARK: getVerify
	public function postVerify(EmailVerificationRequest $request):RedirectResponse
	{
		$request->fulfill();

		return to_route('home')->with('success','you verified your email successfully');
	}

	//MARK: getLogin
	public function getLogin():View
	{
		return view('users.auth.login');
	}

	//MARK: postLogin
	public function postLogin(LoginRequest $request):RedirectResponse
	{
		return $this->authRepository->login($request);
	}

	//MARK: logout
	public function logout(Request $request):RedirectResponse
	{
		$this->authRepository->logoutUser($request);

		return to_route('login');
	}
}
