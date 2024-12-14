<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillController extends Controller
{
	public function __construct(private SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$this->middleware('skill')->only('destroy');
	}

	//MARK: create
	public function create():View
	{
		$skills = $this->skillRepository->getSkills();

		return view('users.skill.create', compact('skills'));
	}

	//MARK: store
	public function store(SkillRequest $request):RedirectResponse
	{
		$this->skillRepository->storeSkill($request, 'user_skill', 'user_id', Auth::id());

		return to_route('skill.create')->with('success','you added skills successfully');
	}

	//MARK: destroy_project_skill
	public function destroy_project_skill(int $skill_id):JsonResponse
	{
		$response = $this->skillRepository->delete_project_skill($skill_id);

		if ($response != null) {
			return $response;
		}

		return response()->json();
	}

	//MARK: destroy
	public function destroy(int $id):JsonResponse
	{
		$response = $this->skillRepository->deleteSkill($id);

		if ($response != null) {
			return $response;
		}

		return response()->json();
	}
}
