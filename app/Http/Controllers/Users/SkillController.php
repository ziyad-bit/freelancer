<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
	private $skillRepository;

	public function __construct(SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$this->skillRepository = $skillRepository;

		$id = basename(url()->full());
		$this->middleware('skill:' . $id)->only('destroy');
	}

	####################################   create   #####################################
	public function create():View
	{
		$skills = $this->skillRepository->getSkills();

		return view('users.skill.create', compact('skills'));
	}

	####################################   store   #####################################
	public function store(SkillRequest $request):JsonResponse
	{
		$this->skillRepository->storeSkill($request);

		return response()->json(['success' => 'you added skills successfully']);
	}

	####################################   destroy   #####################################
	public function destroy(int $id):JsonResponse
	{
		$this->skillRepository->deleteSkill($id);

		return response()->json();
	}
}
