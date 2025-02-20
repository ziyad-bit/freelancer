<?php

namespace App\Interfaces\Repository\Admins;

use App\Http\Requests\DebateRequest;
use App\Http\Requests\ReleaseRequest;
use App\Interfaces\Repository\{FileRepositoryInterface, SkillRepositoryInterface};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

interface DebateRepositoryInterface
{
	public function indexDebate():LengthAwarePaginator;
	public function accessChatDebate(int $initiator_id, int $opponent_id, int $message_id = null):Collection|string;
	public function showDebate(int $id):stdClass;
	public function updateDebate(ReleaseRequest $request,string $transaction):void;
}
