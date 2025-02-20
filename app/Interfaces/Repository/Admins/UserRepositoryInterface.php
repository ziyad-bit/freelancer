<?php

namespace App\Interfaces\Repository\Admins;

use App\Http\Requests\SignupRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

interface UserRepositoryInterface
{
	public function indexUser():LengthAwarePaginator;
	public function storeUser(SignupRequest $request):void;
	public function showUser(string $slug):stdClass;
	public function verifyUser(string $slug):void;
	public function editUser(int $id):stdClass;
	public function updateUser(SignupRequest $request, int $id):void;
	public function deleteUser(int $id):void;
}
