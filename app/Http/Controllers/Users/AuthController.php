<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\Repository\{AuthRepositoryInterface};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\View\View;

class AuthController extends Controller
{
	public function __construct(private AuthRepositoryInterface $authRepository)
	{
		$this->middleware('auth')->only('logout');
		$this->middleware('guest')->except('logout');
	}

	//MARK: create
	public function create():View
	{
		return view('users.auth.signup');
	}

	//MARK: store
	public function store(UserRequest $request):RedirectResponse
	{
		$this->authRepository->storeUser($request);

		return to_route('home');
	}

	//MARK: getLogin
	public function getLogin():View
	{
		return view('users.auth.login');
	}

	//MARK: postLogin
	public function postLogin(UserRequest $request):RedirectResponse
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
