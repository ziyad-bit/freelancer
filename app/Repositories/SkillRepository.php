<?php

namespace App\Repositories;

use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

		$skills_arr = [];
		foreach ($skills as $skill) {
			$skills_arr[] = ['skill_id' => $skill, 'user_id' => Auth::id()];
		}

		DB::table('user_skill')->insert($skills_arr);
	}

	####################################   updateUserInfo   #####################################
	public function deleteSkill(int $id):void
	{
		DB::table('user_skill')->where('id', $id)->delete();
	}
}
