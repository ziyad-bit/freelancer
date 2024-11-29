<?php

namespace App\Repositories;

use App\Http\Requests\ProfileRequest;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};

class ProfileRepository implements ProfileRepositoryInterface
{
	use File;

	//MARK: getUserInfo
	public function getUserInfo(Request $request):array
	{
		$request->session()->regenerate();

		$user_info = DB::table('users')
			->select(
				'location',
				'job',
				'overview',
				DB::raw('Group_concat(Distinct Concat(skill,":",skills.id) ) as skills'),
				DB::raw('ROUND(AVG(rate), 1) as review'),
				DB::raw('IFNULL(transaction_data.total_amount, 0) as total_amount')
			)
			->leftJoin('user_skill', 'users.id', '=', 'user_skill.user_id')
			->leftJoin('skills', 'skills.id', '=', 'user_skill.skill_id')
			->leftJoin('user_infos', 'user_infos.user_id', '=', 'users.id')
			->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
			->leftJoin(
				DB::raw(
					'(
					SELECT receiver_id, SUM(amount) as total_amount 
					FROM transactions 
					WHERE type = "release" 
					GROUP BY receiver_id
					) as transaction_data'
				),
				'transaction_data.receiver_id',
				'=',
				'users.id'
			)
			->where('users.id', Auth::id())
			->groupBy('users.id')
			->first();

		$projects = DB::table('projects')
			->select('title', 'rate', 'amount', 'transactions.created_at', 'projects.id')
			->leftJoin('reviews', 'reviews.project_id', '=', 'projects.id')
			->Join('transactions', 'transactions.project_id', '=', 'projects.id')
			->where('reviews.receiver_id', Auth::id())
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
		$data    = $request->safe()->except('image') + ['user_id' => $user_id];
		$image   = $this->uploadAndResize($request, 199, 'users');

		DB::table('user_infos')->insert($data);

		DB::table('users')->where('id', $user_id)->update(['image' => $image]);

		$request->session()->regenerate();
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
