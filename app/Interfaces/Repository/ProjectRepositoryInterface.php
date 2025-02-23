<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{FilterRequest, ProjectRequest, SearchRequest};

interface ProjectRepositoryInterface
{
	public function fetchProjects(FilterRequest $request):array;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(string $slug):array;
	public function editProject(string $slug):\stdClass;
	public function updateProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository, string $slug):void;
	public function deleteProject(string $slug):void;
}
