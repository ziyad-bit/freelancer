<?php

namespace App\Interfaces\Repository;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
	public function showSkills(string $skill):Collection;
	public function storeSkill(Request $request, string $table, string $column, string $value):void;
	public function delete_project_Skill(int $skill_id):?JsonResponse;
	public function deleteSkill(int $id):?JsonResponse;
}
