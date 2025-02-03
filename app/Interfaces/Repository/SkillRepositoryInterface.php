<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
	public function showSkills(string $skill):Collection;
	public function storeSkill(Request $request, string $table, string $column, int $value):void;
	public function delete_project_Skill(int $skill_id):void;
	public function deleteSkill(int $id):void;
}
