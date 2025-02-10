<?php

namespace App\Interfaces\Repository;

interface SocialiteRepositoryInterface
{
	public function callback(string $provider): void;
}
