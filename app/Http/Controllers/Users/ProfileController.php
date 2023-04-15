<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
	private $profileRepository;

	public function __construct(ProfileRepositoryInterface $profileRepository)
	{
		$this->middleware('auth');

		$this->profileRepository = $profileRepository;
	}

	####################################   index   #####################################
	public function index():View
	{
		$user_info   = $this->profileRepository->getUserInfo();
		$user_skills = $this->profileRepository->getUserSkills();

		return view('users.profile.index', compact('user_info', 'user_skills'));
	}

	####################################   create   #####################################
	public function create():View
	{
		$countries = $this->profileRepository->getCountries();

		return view('users.profile.create', compact('countries'));
	}

	####################################   store   #####################################
	public function store(ProfileRequest $request):RedirectResponse
	{
		$this->profileRepository->storeUserInfo($request);

		return to_route('profile.index')->with('success', 'you add data successfully');
	}

	####################################   show   #####################################
	public function show(int $id):View
	{
		return view('');
	}

	####################################   edit   #####################################
	public function edit():View
	{
		$countries   = $this->profileRepository->getCountries();
		$user_info   = $this->profileRepository->getUserInfo();

		return view('users.profile.edit', compact('user_info', 'countries'));
	}

	####################################   update   #####################################
	public function update(ProfileRequest $request):RedirectResponse
	{
		$this->profileRepository->updateUserInfo($request);

		return to_route('profile.edit', 'auth')->with('success', 'you updated profile successfully');
	}

	####################################   destroy   #####################################
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
