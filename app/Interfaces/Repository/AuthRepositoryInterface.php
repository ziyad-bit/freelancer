<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\UserRequest;
use Illuminate\Http\RedirectResponse;

interface AuthRepositoryInterface
{
	public function login(UserRequest $request):RedirectResponse;
	public function storeUser(UserRequest $request):int;
}
