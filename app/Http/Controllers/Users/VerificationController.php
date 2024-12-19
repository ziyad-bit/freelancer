<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use Illuminate\View\View;
use App\Traits\SendVerification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\{RedirectResponse, Request};
use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Interfaces\Repository\{AuthRepositoryInterface};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Interfaces\Repository\VerificationRepositoryInterface;

class VerificationController extends Controller
{
	use SendVerification;

	public function __construct(private VerificationRepositoryInterface $VerificationRepository)
	{
		$this->middleware('auth');
		$this->middleware('signed')->only('update');
	}

	//MARK: get
	public function get():View
	{
		return view('users.auth.verify_form_email');
	}

	//MARK: send
	public function send():RedirectResponse
	{
		$this->sendVerification(Auth::user());

		return back()->with('success', 'you sent verification link successfully');
	}

	//MARK: update
	public function update(Request $request):RedirectResponse
	{
		$response = $this->VerificationRepository->updateVerify($request);

		if ($response !== null) {
			return $response;
		}

		return to_route('home')->with('success','you verified your email successfully');
	}
}
