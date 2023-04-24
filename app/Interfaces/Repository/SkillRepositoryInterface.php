<?php

namespace App\Interfaces\Repository;

use App\Http\Requests\SkillRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface SkillRepositoryInterface
{
	public function getSkills():Collection;
	public function storeSkill(SkillRequest $request):void;
	public function updateSkill(SkillRequest $request):void;
	public function deleteSkill(Request $request):void;
}
