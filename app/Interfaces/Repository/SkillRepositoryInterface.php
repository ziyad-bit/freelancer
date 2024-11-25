<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
	public function getSkills():Collection;
	public function storeSkill(object $request, string $table, string $column, string $value):void;
	public function delete_project_Skill(int $skill_id):null | RedirectResponse;
	public function deleteSkill(int $id):null | RedirectResponse;
}
