<?php

namespace App\Repositories;

use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SkillRepository implements SkillRepositoryInterface
{
	####################################   storeUserInfo   #####################################
	public function getSkills():Collection
	{
		return DB::table('skills')->get();
	}

	####################################   storeUserInfo   #####################################
	public function storeSkill(SkillRequest $request):void
	{
		$skills    = $request->input('skills_name');

		foreach ($skills as $skill) {
			DB::table('user_skill')->insert(['skill_id' => $skill, 'user_id' => Auth::id()]);
		}
	}

	####################################   updateUserInfo   #####################################
	public function updateSkill(SkillRequest $request):void
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

	####################################   updateUserInfo   #####################################
	public function deleteSkill(Request $request):void
	{
		Validator::make($request->only('password'), [
			'password' => 'required|current_password|string',
		])->validate();

		DB::table('users')->where('id', Auth::id())->delete();

		Auth::logout();
	}
}
