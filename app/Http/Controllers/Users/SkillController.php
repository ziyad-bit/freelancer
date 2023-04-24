<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Interfaces\Repository\SkillRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
	private $skillRepository;

	public function __construct(SkillRepositoryInterface $skillRepository)
	{
		$this->middleware('auth');

		$this->skillRepository = $skillRepository;
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

	####################################   show   #####################################
	public function show(int $id):View
	{
		return view('');
	}

	####################################   edit   #####################################
	public function edit(int $id):View
	{
		return view('');
	}

	####################################   update   #####################################
	public function update($request, int $id):RedirectResponse
	{
		return to_route('');
	}

	####################################   destroy   #####################################
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
