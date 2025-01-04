<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, SignupRequest, SmsVerificationRequest};
use App\Interfaces\Repository\AuthRepositoryInterface;
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
	public function store(SignupRequest $request):View|RedirectResponse
	{
		$message=$this->authRepository->storeUser($request);

		if (isset($message['error']) ) {
			return to_route('signup')->with($message);
		}

		return to_route('get.sms.verify', $message['user_id']);
	}

	//MARK: create
	public function getSmsVerify(int $user_id):View
	{
		return view('users.auth.sms_verification',compact('user_id'));
	}

	//MARK: smsVerify
	public function smsVerify(SmsVerificationRequest $request):RedirectResponse|View
	{
		$message = $this->authRepository->smsVerification($request);

		if (isset($message['error'])) {
			return to_route('get.sms.verify', $request->user_id)->with($message);
		}

		return to_route('home');
	}

	//MARK: getLogin
	public function getLogin():View
	{
		return view('users.auth.login');
	}

	//MARK: postLogin
	public function postLogin(LoginRequest $request):RedirectResponse
	{
		$response = $this->authRepository->login($request);

		if ($response === 'error') {
			return to_route('login')->with(['error' => 'incorrect password or email']);
		}

		return redirect()->intended();
	}

	//MARK: logout
	public function logout(Request $request):RedirectResponse
	{
		$this->authRepository->logoutUser($request);

		return to_route('login');
	}
}
