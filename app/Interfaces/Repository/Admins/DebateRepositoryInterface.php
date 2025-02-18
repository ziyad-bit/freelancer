<?php

namespace App\Interfaces\Repository\Admins;

use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\{DebateRequest, SearchRequest, SignupRequest};
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;

interface DebateRepositoryInterface
{
	public function indexDebate():LengthAwarePaginator;
	public function storeDebate(DebateRequest $request, FileRepositoryInterface $fileRepository, SkillRepositoryInterface $skillRepository):void;
	public function showDebate(int $id):stdClass;
	public function activeDebate(int $id):void;
	public function editDebate(string $slug):stdClass;
	public function updateDebate(DebateRequest $request,FileRepositoryInterface $fileRepository,SkillRepositoryInterface $skillRepository,string $slug):void;
	public function deleteDebate(string $slug):void;
}
