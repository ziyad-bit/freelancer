<?php

namespace App\Interfaces\Repository;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProjectRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ProjectRepositoryInterface
{
	public function getProjects(Request $request):View|JsonResponse;
	public function storeProject(ProjectRequest $request):void;
	public function showProject(int $id):object|null;
	public function download_file(string $file):StreamedResponse;
	public function deleteProject(int $id):void;
}
