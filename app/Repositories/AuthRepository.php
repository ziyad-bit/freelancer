<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\Repository\AuthRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash};

class AuthRepository implements AuthRepositoryInterface
{
	// login   #####################################
	public function login(UserRequest $request):RedirectResponse
	{
		$credentials = $request->only('email', 'password');

		if (auth()->attempt($credentials, $request->filled('remember_me'))) {
			$request->session()->regenerate();

            return redirect()->intended();
		} else {
			return to_route('get.login')->with(['error' => 'incorrect password or email']);
		}
	}

	// storeUser   #####################################
	public function storeUser(UserRequest $request):int
	{
		$data = $request->safe()->except('password') + ['password' => Hash::make($request->password), 'created_at' => now()];

		return DB::table('users')->insertGetId($data);
	}

	// logout   #####################################
	public function logoutUser(Request $request):void
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
