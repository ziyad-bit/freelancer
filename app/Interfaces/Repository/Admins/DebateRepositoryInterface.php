<?php

namespace App\Interfaces\Repository\Admins;

use App\Http\Requests\ReleaseRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

interface DebateRepositoryInterface
{
	public function indexDebate():LengthAwarePaginator;
	public function accessChatDebate(int $initiator_id, int $opponent_id, int $message_id = 0):Collection|string;
	public function showDebate(int $id):stdClass;
	public function updateDebate(ReleaseRequest $request, string $transaction_id):void;
}
