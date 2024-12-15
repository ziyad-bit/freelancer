<?php

namespace App\Repositories;

use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SkillRepository implements SkillRepositoryInterface
{
	//MARK: getSkills
	public function getSkills():Collection
	{
		return DB::table('skills')->limit(350)->get();
	}

	//MARK: storeSkill
	public function storeSkill(object $request, string $table, string $column, string $value):void
	{
		$skills = $request->input('skills');

		if ($skills !== [] && $skills !== null) {
			$skills_arr = [];
			
			foreach ($skills as $skill) {
				if (isset($skill['id'])) {
					$skills_arr[] = [
						'skill_id' => $skill['id'],
						$column    => $value,
					];
				}
			}

			DB::table($table)->insert($skills_arr);
		}
	}

	//MARK: delete_project_Skill
	public function delete_project_Skill(int $skill_id):? RedirectResponse
	{
		$project_skill_query = DB::table('project_skill')->where('id', $skill_id);
		$project_skill       = $project_skill_query->first();

		if (!$project_skill) {
			return response()->json(['error' => 'proposal not found']);
		}

		$project_skill_query->delete();

		return null;
	}

	//MARK: deleteSkill
	public function deleteSkill(int $id):?JsonResponse
	{
		$user_skill_query = DB::table('user_skill')->where('id', $id);
		$user_skill       = $user_skill_query->first();

		if (!$user_skill) {
			return response()->json(['error' => 'something went wrong'], 500);
		}

		$user_skill_query->delete();

		return null;
	}
}
