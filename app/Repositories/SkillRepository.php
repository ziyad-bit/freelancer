<?php

namespace App\Repositories;

use App\Exceptions\GeneralNotFoundException;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SkillRepository implements SkillRepositoryInterface
{
	//MARK: getSkills
	public function showSkills(string $skill):Collection
	{
		return DB::table('skills')
			->where('skill', 'like', "{$skill}%")
			->limit(5)
			->get();
	}

	//MARK: storeSkill
	public function storeSkill(Request $request, string $table, string $column, string $value):void
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
	public function delete_project_Skill(int $id):void
	{
		$project_skill_query = DB::table('project_skill')->where('id', $id);
		$project_skill       = $project_skill_query->first();

		if (!$project_skill) {
			throw new GeneralNotFoundException('skill');
		}

		$project_skill_query->delete();
	}

	//MARK: deleteSkill
	public function deleteSkill(int $id):void
	{
		$user_skill_query = DB::table('user_skill')->where('id', $id);
		$user_skill       = $user_skill_query->first();

		if (!$user_skill) {
			throw new GeneralNotFoundException('skill');
		}

		$user_skill_query->delete();
	}
}
