<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\AdminRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminsController extends Controller
{
	public function __construct(private AdminRepositoryInterface $adminRepository)
	{
		$this->middleware('auth:admins');
	}

	// MARK: index
	public function index():View
	{
		$admins = $this->adminRepository->indexAdmin();

		return view('admins.admin.index', compact('admins'));
	}

	// MARK: create
	public function create():View
	{
		return view('admins.admin.create');
	}

	// MARK: store
	public function store(SignupRequest $request):RedirectResponse
	{
		$this->adminRepository->storeAdmin($request);

		return to_route('admin.admin.create')->with('success', 'admin is created successfully');
	}

	// MARK: show
	public function show(string $slug):View
	{
		$admin = $this->adminRepository->showAdmin($slug);

		return view('admins.admin.show', compact('admin'));
	}

	// MARK: verify
	public function verify(string $slug):RedirectResponse
	{
		$this->adminRepository->verifyAdmin($slug);

		return to_route('admin.admin.show',$slug)->with('success', 'admin is verified successfully');
	}

	// MARK: edit
	public function edit(int $id):View
	{
		$admin = $this->adminRepository->editAdmin($id);

		return view('admins.admin.edit', compact('admin'));
	}

	// MARK: update
	public function update(SignupRequest $request, int $id):RedirectResponse
	{
		$this->adminRepository->updateAdmin($request, $id);

		return to_route('admin.admin.edit', $id)->with('success', 'admin is updated successfully');
	}

	// MARK: destroy
	public function destroy(int $id):RedirectResponse
	{
		$this->adminRepository->deleteAdmin($id);

		return to_route('admin.admin.index')->with('success', 'admin is deleted successfully');
	}
}
