<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Traits\SendSmsVerification;
use App\Traits\sendEmailVerification;
use Illuminate\Support\Facades\{Auth, Cache, DB, Hash};
use App\Http\Requests\{LoginRequest, SignupRequest, SmsVerificationRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
	use sendEmailVerification, SendSmsVerification;

	//MARK: store User   
	public function storeUser(SignupRequest $request):array
	{
		$data = $request->safe()->except('password') +
			[
				'password'   => Hash::make($request->password),
				'created_at' => now(),
			];

		$user_id = DB::table('users')->insertGetId($data);

		$this->sendEmailVerification($request->email, $user_id);

		return $this->sendSmsVerification($user_id, $request->phone_number);
	}

	//MARK: smsVerification   
	public function smsVerification(SmsVerificationRequest $request):array
	{
		if (Cache::has('code_num_'.$request->user_id)) {
			$code_num = Cache::get('code_num_'.$request->user_id);

			if ($request->code_num === $code_num) {
				Cache::forget('code_num_'.$request->user_id);

				Auth::loginUsingId($request->user_id);

				return ['success' => 'you are logged in'];
			} else {
				return ['error' => 'incorrect code'];
			}
		} else {
			return ['error' => 'code expired'];
		}
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
