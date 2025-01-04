<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\VerificationRepositoryInterface;
use App\Traits\SendEmailVerification;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerificationController extends Controller
{
	use SendEmailVerification;

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
		$this->sendEmailVerification(Auth::user());

		return back()->with('success', 'you sent verification link successfully');
	}

	//MARK: update
	public function update(Request $request):RedirectResponse
	{
		$message = $this->VerificationRepository->updateVerify($request);

		if (isset($message['error'])) {
			return to_route('verification.get')->with($message);
		}

		return to_route('home')->with($message);
	}
}
