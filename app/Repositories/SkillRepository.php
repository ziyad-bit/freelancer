<?php

namespace App\Repositories;

use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SkillRepository implements SkillRepositoryInterface
{
	####################################   storeUserInfo   #####################################
	public function getSkills():Collection
	{
		return DB::table('skills')->get();
	}

	####################################   storeUserInfo   #####################################
	public function storeSkill(object $request,string $table,string $column,string $value):void
	{
		$skills    = $request->input('skills_id');

		$skills_arr = [];
		foreach ($skills as $skill) {
			$skills_arr[] = [
				'skill_id' => $skill,
				$column    => $value,
			];
		}

		DB::table($table)->insert($skills_arr);
	}

	####################################   updateUserInfo   #####################################
	public function delete_project_Skill(int $skill_id):void
	{
		DB::table('project_skill')->where(['id' => $skill_id])->delete();
	}

	####################################   updateUserInfo   #####################################
	public function deleteSkill(int $id):void
	{
		DB::table('user_skill')->where('id', $id)->delete();
	}
}
