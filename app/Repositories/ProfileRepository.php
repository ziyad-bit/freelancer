<?php

namespace App\Repositories;

use App\Http\Requests\ProfileRequest;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\File;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Auth, DB, Validator};

class ProfileRepository implements ProfileRepositoryInterface
{
	use File;

	// MARK: getUserSkills
	public function getUserSkills(): Collection
	{
		return DB::table('users')
			->select('user_skill.id', 'skills.skill')
			->where('users.id', Auth::id())
			->join('user_skill', 'users.id', '=', 'user_skill.user_id')
			->join('skills', 'skills.id', '=', 'user_skill.skill_id')
			->get();
	}

	//MARK: getUserInfo
	public function getUserInfo(Request $request):object|null
	{
		$request->session()->regenerate();

		return DB::table('user_infos')->where('user_id', Auth::id())->first();
	}

	//MARK: storeUserInfo
	public function storeUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image') + ['user_id' => $user_id];
		$image   = $this->uploadAndResize($request, 199, 'users');

		DB::table('user_infos')->insert($data);

		DB::table('users')->where('id', $user_id)->update(['image' => $image]);

		$request->session()->regenerate();
	}

	//MARK: updateUserInfo
	public function updateUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image');

		DB::table('user_infos')->where('user_id', $user_id)->update($data);

		if ($request->has('image')) {
			$user_query = DB::table('users')->where('id', $user_id);
			$old_image  = $user_query->value('image');

			$new_image = $this->updateImage($request, 199, $old_image);

			$user_query->update(['image' => $new_image]);
		}

		$request->session()->regenerate();
	}

	//MARK: deleteUserInfo
	public function deleteUserInfo(Request $request):void
	{
		Validator::make(
			$request->only('password'),
			[
				'password' => 'required|current_password|string',
			]
		)->validate();

		DB::table('users')->where('id', Auth::id())->delete();

		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
