<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

interface ProjectRepositoryInterface
{
	public function getProjects(Request $request):View|JsonResponse;
	public function storeProject(ProjectRequest $request):void;
	public function deleteProject(int $id):void;
}
