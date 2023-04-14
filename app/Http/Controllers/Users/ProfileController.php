<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
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
	public function store($request):RedirectResponse
	{
		return to_route('');
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
