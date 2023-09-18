<?php

namespace App\Repositories;

use App\Traits\UploadPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Validator;
use App\Classes\AbstractFactory\FileAbstractFactory;
use App\Interfaces\Repository\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
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

	####################################   storeUserInfo   #####################################
	public function storeUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image') + ['user_id' => $user_id];
		$image   = (new FileAbstractFactory())->create_image()->uploadAndResize($request, 199, 'users');

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

			$new_image =(new FileAbstractFactory())->create_image()->update($request, 199, $old_image);

			DB::table('users')->where('id', $user_id)->update(['image' => $new_image]);
		}
	}

	####################################   updateUserInfo   #####################################
	public function deleteUserInfo(Request $request):void
	{
		Validator::make($request->only('password'), [
			'password' => 'required|current_password|string',
		])->validate();

		DB::table('users')->where('id', Auth::id())->delete();

		Auth::logout();
	}
}
