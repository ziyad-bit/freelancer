<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->only(['logout', 'index']);
		$this->middleware('guest')->except(['logout', 'index']);
	}

	####################################   getLogin   #####################################
	public function getLogin():View
	{
		return view('users.auth.login');
	}

	####################################   postLogin   #####################################
	public function postLogin(UserRequest $request):RedirectResponse
	{
		$credentials = $request->only('email', 'password');

		if (auth()->attempt($credentials, $request->filled('remember_me'))) {
			return to_route('home');
		} else {
			return to_route('get.login')->with(['error' => 'incorrect password or email']);
		}
	}

	####################################   index   #####################################
	public function index():View
	{
		return view('users.auth.home');
	}

	####################################   create   #####################################
	public function create():View
	{
		return view('users.auth.signup');
	}

	####################################   store   #####################################
	public function store(UserRequest $request):RedirectResponse
	{
		$data = $request->safe()->except('password') + ['password' => Hash::make($request->password), 'created_at' => now()];

		$user_id = DB::table('users')->insertGetId($data);

		Auth::loginUsingId($user_id);

		return to_route('home');
	}

	####################################   logout   #####################################
	public function logout():RedirectResponse
	{
		Auth::logout();

		return to_route('get.login');
	}
}
