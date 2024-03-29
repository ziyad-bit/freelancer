<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProjectRequest;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Collection;
use Illuminate\View\View;

interface ProjectRepositoryInterface
{
	public function getProjects(Request $request):View|JsonResponse;
	public function createProject(Collection $skills):View;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(int $id):object|null;
	public function editProject(int $id, Collection $skills):RedirectResponse|View;
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):RedirectResponse;
	public function deleteProject(int $id):RedirectResponse;
}
