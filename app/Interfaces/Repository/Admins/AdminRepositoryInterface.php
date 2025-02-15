<?php

namespace App\Interfaces\Repository\Admins;

use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\{ProjectRequest, SearchRequest, SignupRequest};

interface AdminRepositoryInterface
{
	public function indexAdmin():LengthAwarePaginator;
	public function storeAdmin(SignupRequest $request):void;
	public function showAdmin(int $id):stdClass;
	public function editAdmin(int $id):stdClass;
	public function updateAdmin(SignupRequest $request,int $id):void;
	public function deleteAdmin(int $id):void;
}
