<?php

namespace App\Interfaces\Repository;

use Illuminate\View\View;
use Illuminate\Support\Collection;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};

interface ProjectRepositoryInterface
{
	public function getProjects(SearchRequest $request):View|JsonResponse;
	public function createProject(Collection $skills):View;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(int $id):object|null;
	public function editProject(int $id, Collection $skills):RedirectResponse|View;
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse;
	public function deleteProject(int $id):RedirectResponse;
}
