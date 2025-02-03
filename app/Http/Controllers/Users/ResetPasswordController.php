<?php

namespace App\Http\Controllers\Users;

use App\Mail\VerifyEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use App\Traits\SendEmailVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ResetPasswordRequest;
use App\Interfaces\Repository\ResetPasswordRepositoryInterface;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\{RedirectResponse, Request};
use App\Interfaces\Repository\VerificationRepositoryInterface;

class ResetPasswordController extends Controller
{
	public function __construct(private ResetPasswordRepositoryInterface $ResetPasswordRepository)
	{
		$this->middleware('signed')->only('edit');
		$this->middleware('guest');
	}

	//MARK: get
	public function get():View
	{
		return view('users.auth.reset_password_send');
	}

	//MARK: send
	public function send(ResetPasswordRequest $request):RedirectResponse
	{
		$this->ResetPasswordRepository->sendLink($request);

		return back()->with('success', 'you will receive an email with a link to reset your password');
	}

	//MARK: update
	public function edit(string $email,string $token):View
	{
		return view('users.auth.reset_password_edit',compact('email','token'));
	}

	//MARK: update
	public function update(ResetPasswordRequest $request):RedirectResponse
	{
		$message = $this->ResetPasswordRepository->updatePassword($request);
		
		return to_route('profile.index',Auth::user()->slug)->with($message);
	}
}
