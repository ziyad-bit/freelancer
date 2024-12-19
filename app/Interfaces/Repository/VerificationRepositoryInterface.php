<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{LoginRequest, SignupRequest};
use Illuminate\Http\{RedirectResponse, Request};

interface VerificationRepositoryInterface
{
	public function updateVerify(Request $request):?RedirectResponse;
}
