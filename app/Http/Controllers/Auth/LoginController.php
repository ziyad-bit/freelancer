<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admins')->except('logout');
        $this->middleware('auth:admins')->only('logout');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

		if (auth()->guard('admins')->attempt($credentials, $request->filled('remember'))) {
			return redirect()->intended('admin/home');
        } else {
            return redirect()->back()->with(['error' => 'incorrect password or email']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admins')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('admin/login');
    }
}
