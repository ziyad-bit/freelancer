<?php

namespace App\Interfaces\Repository\Admins;

use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\{ProjectRequest, SearchRequest, SignupRequest};

interface ProjectRepositoryInterface
{
	public function indexProject():LengthAwarePaginator;
	public function storeProject(ProjectRequest $request):void;
	public function showProject(int $id):stdClass;
	public function editProject(int $id):stdClass;
	public function updateProject(ProjectRequest $request,int $id):void;
	public function deleteProject(int $id):void;
}
