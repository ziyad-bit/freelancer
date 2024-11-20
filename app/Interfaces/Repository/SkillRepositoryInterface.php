<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;
use App\Http\Requests\SkillRequest;
use Illuminate\Http\RedirectResponse;

interface SkillRepositoryInterface
{
	public function getSkills():Collection;
	public function storeSkill(SkillRequest $request, string $table, string $column, string $value):void;
	public function delete_project_Skill(int $skill_id):null | RedirectResponse;
	public function deleteSkill(int $id):null | RedirectResponse;
}
