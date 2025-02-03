<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\SendEmailVerification;
use App\Interfaces\Repository\AuthRepositoryInterface;
use Illuminate\Support\Facades\{Auth, Cache, DB, Hash};
use App\Http\Requests\{LoginRequest, SignupRequest, SmsVerificationRequest};
use App\Traits\Slug;

class AuthRepository implements AuthRepositoryInterface
{
	use SendEmailVerification,Slug;

	//MARK: store User   
	public function storeUser(SignupRequest $request):string
	{
		$slug = $this->createSlug('users','name',$request->name);

		$data = $request->safe()->except('password') +
			[
				'password'   => $request->password,
				'created_at' => now(),
				'slug'       => $slug,
			];

		$user_id = DB::table('users')->insertGetId($data);

		Auth::loginUsingId($user_id);

		$this->sendEmailVerification($request->email, $user_id);

		return $slug;
	}

	//MARK: login   
	public function login(LoginRequest $request):?string
	{
		$credentials = $request->only('email', 'password');

		if (!auth()->attempt($credentials, $request->filled('remember_me'))) {
			return 'error';
		}

		request()->session()->regenerate();

		return null;
	}

	//MARK: logout   
	public function logoutUser(Request $request):void
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
