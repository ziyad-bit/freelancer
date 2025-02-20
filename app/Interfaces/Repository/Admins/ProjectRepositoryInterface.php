<?php

namespace App\Interfaces\Repository\Admins;

use App\Http\Requests\ProjectRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

interface ProjectRepositoryInterface
{
	public function indexProject():LengthAwarePaginator;
	public function storeProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showProject(string $slug):array;
	public function activeProject(int $id):void;
	public function editProject(string $slug):stdClass;
	public function updateProject(ProjectRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository, string $slug):void;
	public function deleteProject(string $slug):void;
}
