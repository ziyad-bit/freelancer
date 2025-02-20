<?php

namespace App\Interfaces\Repository\Admins;

use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\{DebateRequest, SearchRequest, SignupRequest};
use App\Interfaces\Repository\FileRepositoryInterface;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Support\Collection;

interface DebateRepositoryInterface
{
	public function indexDebate():LengthAwarePaginator;
	public function accessChatDebate(int $initiator_id,int $opponent_id,int $message_id=null):Collection;
	public function showDebate(int $id):stdClass;
	public function activeDebate(int $id):void;
	public function editDebate(string $slug):stdClass;
	public function updateDebate(DebateRequest $request,FileRepositoryInterface $fileRepository,SkillRepositoryInterface $skillRepository,string $slug):void;
	public function deleteDebate(string $slug):void;
}
