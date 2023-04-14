<?php

namespace App\Repositories;

use App\Interfaces\Repository\ProfileRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProfileRepository implements ProfileRepositoryInterface
{
	public function getUserSkills(): Collection
	{
		return DB::table('users')
				->select('user_skill.id', 'skills.skill')
				->where('users.id', Auth::id())
				->join('user_skill', 'users.id', '=', 'user_skill.user_id')
				->join('skills', 'skills.id', '=', 'user_skill.skill_id')
				->get();
	}

	public function getUserInfo():object|null
	{
		return DB::table('user_infos')->where('user_id', Auth::id())->first();
	}

	public function getCountries(): array
	{
		$response = Http::get('https://restcountries.com/v3.1/all?fields=name');

		return $response->collect()->sortBy('name')->toArray();
	}
}
