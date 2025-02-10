<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\DebateRequest;
use App\Interfaces\Repository\AuthProjectRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthProjectController extends Controller
{
	public function __construct(private AuthProjectRepositoryInterface $authProjectRepository)
	{
	}

	// MARK: index
	public function index():View
	{
		$projects = $this->authProjectRepository->indexAuthProjects();

		return view('users.authProject.index', compact('projects'));
	}

	// MARK: create
	public function debate_create(int $project_id, int $user_id):View
	{
		return view('users.authProject.debate_create', compact('project_id', 'user_id'));
	}

	// MARK: store
	public function debate_store(DebateRequest $request):RedirectResponse
	{
		$this->authProjectRepository->storeDebateData($request);

		return to_route('auth.project.index')->with('success', 'record is created successfully');
	}
}
