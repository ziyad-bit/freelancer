<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\GetCountries;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\View\View;

class ProfileController extends Controller
{
	use GetCountries;

	public function __construct(private ProfileRepositoryInterface $profileRepository)
	{
		$this->middleware('auth');
		$this->middleware('profile')->only(['create', 'store']);
	}

	//MARK: index
	public function index(Request $request):View
	{
		$user_info   = $this->profileRepository->getUserInfo($request);
		$user_skills = $this->profileRepository->getUserSkills();

		return view('users.profile.index',compact('user_info','user_skills'));
	}

	//MARK: create
	public function create():View
	{
		$countries = $this->getCountries();

		return view('users.profile.create', compact('countries'));
	}

	//MARK: store
	public function store(ProfileRequest $request):RedirectResponse
	{
		$this->profileRepository->storeUserInfo($request);

		return to_route('profile.index')->with('success', 'you add data successfully');
	}

	//MARK: edit
	public function edit(Request $request):View
	{
		$countries   = $this->getCountries();
		$user_info   = $this->profileRepository->getUserInfo($request);

		return view('users.profile.edit', compact('user_info', 'countries'));
	}

	//MARK: update
	public function update(ProfileRequest $request):RedirectResponse
	{
		$this->profileRepository->updateUserInfo($request);

		return to_route('profile.edit', 'auth')->with('success', 'you updated profile successfully');
	}

	//MARK: show
	public function delete():View
	{
		return view('users.profile.delete');
	}

	//MARK: destroy
	public function destroy(Request $request):RedirectResponse
	{
		$this->profileRepository->deleteUserInfo($request);

		return to_route('login');
	}
}
