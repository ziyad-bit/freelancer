<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
	public function getSkills():Collection;
	public function storeSkill(object $request, string $table, string $column, string $value):void;
	public function delete_project_Skill(int $skill_id):void;
	public function deleteSkill(int $id):void;
}
