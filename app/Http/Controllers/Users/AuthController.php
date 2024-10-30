<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\Repository\{AuthRepositoryInterface, ProfileRepositoryInterface};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
	private $authRepository;
	private $profileRepository;

	public function __construct(AuthRepositoryInterface $authRepository, ProfileRepositoryInterface $profileRepository)
	{
		$this->middleware('auth')->only(['logout', 'index']);
		$this->middleware('guest')->except(['logout', 'index']);

		$this->authRepository    = $authRepository;
		$this->profileRepository = $profileRepository;
	}

	// getLogin   #####################################
	public function getLogin():View
	{
		return view('users.auth.login');
	}

	// postLogin   #####################################
	public function postLogin(UserRequest $request):RedirectResponse
	{
		return $this->authRepository->login($request);
	}

	// index   #####################################
	public function index(Request $request):View
	{
		$user_info = $this->profileRepository->getUserInfo($request);

		return view('users.auth.home', compact('user_info'));
	}

	// create   #####################################
	public function create():View
	{
		return view('users.auth.signup');
	}

	// store   #####################################
	public function store(UserRequest $request):RedirectResponse
	{
		$user_id = $this->authRepository->storeUser($request);

		Auth::loginUsingId($user_id);

		return to_route('home');
	}

	// logout   #####################################
	public function logout(Request $request):RedirectResponse
	{
		$this->authRepository->logoutUser($request);

		return to_route('login');
	}
}
