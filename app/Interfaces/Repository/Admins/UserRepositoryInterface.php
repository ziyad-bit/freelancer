<?php

namespace App\Interfaces\Repository\Admins;

use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\{ProjectRequest, SearchRequest, SignupRequest};

interface UserRepositoryInterface
{
	public function indexUser():LengthAwarePaginator;
	public function storeUser(SignupRequest $request):void;
	public function showUser(string $slug):stdClass;
	public function verifyUser(string $slug):void;
	public function editUser(int $id):stdClass;
	public function updateUser(SignupRequest $request,int $id):void;
	public function deleteUser(int $id):void;
}
