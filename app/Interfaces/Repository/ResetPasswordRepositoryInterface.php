<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\Request;

interface ResetPasswordRepositoryInterface
{
	public function sendLink(Request $request):void;
	public function updatePassword(Request $request):array;
}
