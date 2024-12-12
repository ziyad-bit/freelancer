<?php

namespace App\Repositories;

use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB, Hash};

class AuthRepository implements AuthRepositoryInterface
{
	// storeUser   #####################################
	public function storeUser(SignupRequest $request):void
	{
		$data = $request->safe()->except('password') + ['password' => Hash::make($request->password), 'created_at' => now()];

		$user_id = DB::table('users')->insertGetId($data);

		Auth::loginUsingId($user_id);
	}

	// login   #####################################
	public function login(LoginRequest $request):RedirectResponse
	{
		$credentials = $request->only('email', 'password');

		if (auth()->attempt($credentials, $request->filled('remember_me'))) {
			$request->session()->regenerate();

			return redirect()->intended();
		} else {
			return to_route('login')->with(['error' => 'incorrect password or email']);
		}
	}

	// logout   #####################################
	public function logoutUser(Request $request):void
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
