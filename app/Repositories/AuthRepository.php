<?php

namespace App\Repositories;

use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;
use App\Traits\{SendEmailVerification, Slug};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class AuthRepository implements AuthRepositoryInterface
{
	use SendEmailVerification,Slug;

	//MARK: store User
	public function storeUser(SignupRequest $request):string
	{
		$slug = $this->createSlug('users', 'name', $request->name);

		$data = $request->safe()->except('password') +
			[
				'password'   => $request->password,
				'created_at' => now(),
				'slug'       => $slug,
			];

		$user_id = DB::table('users')->insertGetId($data);

		Auth::guard('web')->loginUsingId($user_id);

		$this->sendEmailVerification($request->email, $user_id);

		return $slug;
	}

	//MARK: login
	public function login(LoginRequest $request):?string
	{
		$credentials = $request->only('email', 'password');

		if (!auth()->guard('web')->attempt($credentials, $request->filled('remember_me'))) {
			return 'error';
		}

		request()->session()->regenerate();

		return null;
	}

	//MARK: logout
	public function logoutUser(Request $request):void
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
