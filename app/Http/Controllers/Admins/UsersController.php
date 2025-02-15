<?php

namespace App\Http\Controllers\Admins;

use App\Classes\User;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\UserRepositoryInterface;
use App\Traits\Slug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UsersController extends Controller
{
	public function __construct(private UserRepositoryInterface $userRepository)
	{
		$this->middleware('auth:admins');
	}

	// MARK: index
	public function index():View
	{
		$users = $this->userRepository->indexUser();

		return view('admins.user.index', compact('users'));
	}

	// MARK: create
	public function create():View
	{
		return view('admins.user.create');
	}

	// MARK: store
	public function store(SignupRequest $request):RedirectResponse
	{
		$this->userRepository->storeUser($request);

		return to_route('admin.user.create')->with('success', 'User is created successfully');
	}

	// MARK: show
	public function show(string $slug):View
	{
		$user = $this->userRepository->showUser($slug);

		return view('admins.user.show', compact('user'));
	}

	// MARK: verify
	public function verify(string $slug):RedirectResponse
	{
		$this->userRepository->verifyUser($slug);

		return to_route('admin.user.show',$slug)->with('success', 'User is verified successfully');
	}

	// MARK: edit
	public function edit(int $id):View
	{
		$user = $this->userRepository->editUser($id);

		return view('admins.user.edit', compact('user'));
	}

	// MARK: update
	public function update(SignupRequest $request, int $id):RedirectResponse
	{
		$this->userRepository->updateUser($request, $id);

		return to_route('admin.user.edit', $id)->with('success', 'User is updated successfully');
	}

	// MARK: destroy
	public function destroy(int $id):RedirectResponse
	{
		$this->userRepository->deleteUser($id);

		return to_route('admin.user.index')->with('success', 'User is deleted successfully');
	}
}
