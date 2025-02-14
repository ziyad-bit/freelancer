<?php

namespace App\Repositories;

use App\Traits\File;
use App\Classes\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Interfaces\Repository\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
	use File;

	//MARK: getUserInfo
	public function getUserInfo(string $slug):array
	{
		$user_info = User::index($slug);

		//show the last 10 projects which the user has received the money
		$projects = DB::table('projects')
			->select('title', 'rate', 'amount', 'transactions.created_at', 'projects.id')
			->leftJoin('reviews', 'reviews.project_id', '=', 'projects.id')
			->Join('transactions', 'transactions.project_id', '=', 'projects.id')
			->where('reviews.receiver_id', $user_info->id)
			->where('type', 'release')
			->latest('transactions.created_at')
			->limit(10)
			->get();

		return ['projects' => $projects, 'user_info' => $user_info];
	}

	//MARK: storeUserInfo
	public function storeUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image','front_id_image','back_id_image') 
					+ ['user_id' => $user_id];

		$front_id_image = $this->uploadAndResize($request,300 ,'users','front_id_image');
		$back_id_image  = $this->uploadAndResize($request,300 ,'users','back_id_image');
		$image          = $this->uploadAndResize($request, 199, 'users');

		$data= $data + ['front_id_image' => $front_id_image, 'back_id_image' => $back_id_image];

		DB::table('user_infos')->insert($data);

		DB::table('users')->where('id', $user_id)->update(['image' => $image]);

		$request->session()->regenerate();
	}

	//MARK: editUserInfo
	public function editUserInfo():?object
	{
		request()->session()->regenerate();

		return DB::table('user_infos')->where('user_id', Auth::id())->first();
	}

	//MARK: updateUserInfo
	public function updateUserInfo(ProfileRequest $request):void
	{
		$user_id = Auth::id();
		$data    = $request->safe()->except('image');

		DB::table('user_infos')->where('user_id', $user_id)->update($data);

		if ($request->has('image')) {
			$user_query = DB::table('users')->where('id', $user_id);
			$old_image  = $user_query->value('image');

			$new_image = $this->updateImage($request, 199, $old_image);

			$user_query->update(['image' => $new_image]);
		}

		$request->session()->regenerate();
	}

	//MARK: deleteUserInfo
	public function deleteUserInfo(Request $request):void
	{
		Validator::make(
			$request->only('password'),
			[
				'password' => 'required|current_password|string',
			]
		)->validate();

		DB::table('users')->where('id', Auth::id())->delete();

		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
	}
}
