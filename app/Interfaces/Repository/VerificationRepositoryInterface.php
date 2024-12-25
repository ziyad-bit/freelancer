<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\Request;

interface VerificationRepositoryInterface
{
	public function updateVerify(Request $request):array;
}
