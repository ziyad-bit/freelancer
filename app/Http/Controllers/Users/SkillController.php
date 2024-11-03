<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
	public function __construct(private SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$id = request()->route('skill');
		$this->middleware('skill:' . $id)->only('destroy');
	}

	//MARK: create   
	public function create():View
	{
		$skills = $this->skillRepository->getSkills();

		return view('users.skill.create', compact('skills'));
	}

	//MARK: store   
	public function store(SkillRequest $request):JsonResponse
	{
		$this->skillRepository->storeSkill($request, 'user_skill', 'user_id', Auth::id());

		return response()->json(['success' => 'you added skills successfully']);
	}

	//MARK: destroy_project_skill  
	public function destroy_project_skill(int $skill_id):JsonResponse
	{
		$this->skillRepository->delete_project_skill($skill_id);

		return response()->json();
	}

	//MARK: destroy   
	public function destroy(int $id):JsonResponse
	{
		$this->skillRepository->deleteSkill($id);

		return response()->json();
	}
}
