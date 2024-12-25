<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
	public function __construct(private SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$this->middleware('skill')->only('destroy');
		$this->middleware('project_skill')->only('destroy_project_skill');
	}

	//MARK: create
	public function create():View
	{
		return view('users.skill.create');
	}

	//MARK: store
	public function store(SkillRequest $request):RedirectResponse
	{
		$this->skillRepository->storeSkill($request, 'user_skill', 'user_id', Auth::id());

		return to_route('skill.create')->with('success', 'you added skills successfully');
	}

	//MARK: show
	public function show(string $skill):JsonResponse
	{
		$skills = $this->skillRepository->showSkills($skill);

		return response()->json(['skills' => $skills]);
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
