<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{ProjectRequest, SearchRequest};
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
	public function fetchProjects(SearchRequest $request):array|JsonResponse;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(int $id):JsonResponse|RedirectResponse|array;
	public function editProject(int $id, Collection $skills):object;
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse|null;
	public function deleteProject(int $id):RedirectResponse|null;
}
