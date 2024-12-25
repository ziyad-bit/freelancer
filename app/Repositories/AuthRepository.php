<?php

namespace App\Repositories;

use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Traits\SendVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash};

class AuthRepository implements AuthRepositoryInterface
{
	use SendVerification;
	// storeUser   #####################################
	public function storeUser(SignupRequest $request):void
	{
		$data = $request->safe()->except('password') + ['password' => Hash::make($request->password), 'created_at' => now()];

		$user_id = DB::table('users')->insertGetId($data);

		Auth::loginUsingId($user_id);

		$this->sendVerification($request->user());
	}

	// login   #####################################
	public function login(LoginRequest $request):?string
	{
		$credentials = $request->only('email', 'password');

		if (!auth()->attempt($credentials, $request->filled('remember_me'))) {
			return 'error';
		}

		request()->session()->regenerate();

		return null;
	}

	// logout   #####################################
	public function logoutUser(Request $request):void
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
