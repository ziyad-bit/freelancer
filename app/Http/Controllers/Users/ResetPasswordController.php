<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Interfaces\Repository\ResetPasswordRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
	public function edit(string $email, string $token):View
	{
		return view('users.auth.reset_password_edit', compact('email', 'token'));
	}

	//MARK: update
	public function update(ResetPasswordRequest $request):RedirectResponse
	{
		$message = $this->ResetPasswordRepository->updatePassword($request);

		return to_route('profile.get', Auth::user()->slug)->with($message);
	}
}
