<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\ProposalRequest;

interface SocialiteRepositoryInterface
{
	public function callback(string $provider): void;
}
