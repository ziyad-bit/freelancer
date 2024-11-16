<?php

namespace App\Repositories;

use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SkillRepository implements SkillRepositoryInterface
{
	//MARK: getSkills  
	public function getSkills():Collection
	{
		return DB::table('skills')->limit(50)->get();
	}

	//MARK: storeSkill   
	public function storeSkill(object $request, string $table, string $column, string $value):void
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

	//MARK: delete_project_Skill   
	public function delete_project_Skill(int $skill_id):void
	{
		$project_skill_query = DB::table('project_skill')->where('id' , $skill_id);
		$project_skill       = $project_skill_query->first();

		if (!$project_skill) {
			return redirect()->back()->with('error', 'proposal not found');
		}

		$project_skill_query->delete();
	}

	//MARK: deleteSkill   
	public function deleteSkill(int $id):void
	{
		$user_skill_query = DB::table('user_skill')->where('id' , $id);
		$user_skill       = $user_skill_query->first();

		if (!$user_skill) {
			return redirect()->back()->with('error', 'proposal not found');
		}

		$user_skill_query->delete();
	}
}
