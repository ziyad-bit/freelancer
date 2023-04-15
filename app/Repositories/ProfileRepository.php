<?php

namespace App\Repositories;

use App\Http\Requests\ProfileRequest;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\UploadPhoto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProfileRepository implements ProfileRepositoryInterface
{
	use UploadPhoto;

	####################################   getUserSkills   #####################################
	public function getUserSkills(): Collection
	{
		return DB::table('users')
				->select('user_skill.id', 'skills.skill')
				->where('users.id', Auth::id())
				->join('user_skill', 'users.id', '=', 'user_skill.user_id')
				->join('skills', 'skills.id', '=', 'user_skill.skill_id')
				->get();
	}

	####################################   getUserInfo   #####################################
	public function getUserInfo():object|null
	{
		return DB::table('user_infos')->where('user_id', Auth::id())->first();
	}

	####################################   getCountries   #####################################
	public function getCountries(): array
	{
		$response = Http::get('https://restcountries.com/v3.1/all?fields=name');

		return $response->collect()->sortBy('name')->toArray();
	}

	####################################   storeUserInfo   #####################################
	public function storeUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image') + ['user_id' => $user_id];
		$image   = $this->uploadPhoto($request, 199);

		DB::table('user_infos')->insert($data);

		DB::table('users')->where('id', $user_id)->update(['image' => $image]);
	}

	####################################   updateUserInfo   #####################################
	public function updateUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image');

		DB::table('user_infos')->where('user_id', $user_id)->update($data);

		if ($request->has('image')) {
			$old_image = DB::table('users')->where('id', $user_id)->value('image');

			$new_image = $this->updatePhoto($request, 199, $old_image);

			DB::table('users')->where('id', $user_id)->update(['image' => $new_image]);
		}
	}
}
