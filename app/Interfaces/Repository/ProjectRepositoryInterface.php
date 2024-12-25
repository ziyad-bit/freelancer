<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\{ProjectRequest, SearchRequest};

interface ProjectRepositoryInterface
{
	public function fetchProjects(SearchRequest $request):array;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(int $id):array;
	public function editProject(int $id):\stdClass;
	public function updateProject(ProjectRequest $request, int $id, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function deleteProject(int $id):void;
}
