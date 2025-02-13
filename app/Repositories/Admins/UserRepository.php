<?php

namespace App\Repositories\Admins;

use App\Classes\User;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\UserRepositoryInterface as AdminsUserRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Traits\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use stdClass;

class UserRepository implements AdminsUserRepositoryInterface
{
	//MARK: getUser
	public function indexUser():LengthAwarePaginator
	{
		return DB::table('users')
			->select('id', 'name', 'slug')
			->paginate(10);
	}

	//MARK: storeUser
	public function storeUser(SignupRequest $request):void
	{
		$slug = $this->createSlug('users', 'name', $request->name);

		$data = $request->safe()->except('password') +
			[
				'password'   => $request->password,
				'created_at' => now(),
				'slug'       => $slug,
			];

		DB::table('users')->insert($data);
	}

	//MARK: editUser
	public function editUser(int $id):stdClass
	{
		$user_query = DB::table('users')->where('id', $id);
		$user_id    = $user_query->value('id');

		if (!$user_id) {
			throw new GeneralNotFoundException('User');
		}

		return $user_query->first();
	}

	//MARK: updateUser
	public function updateUser(SignupRequest $request,int $id):void
	{
		$user_query = DB::table('users')->where('id', $id);
		$user_id    = $user_query->value('id');

		if (!$user_id) {
			throw new GeneralNotFoundException('User');
		}

		if ($request->has('password')) {
			$data = $request->safe()->except('password') + ['password' => $request->password];
		} else {
			$data = $request->validated() + ['updated_at' => now()];
		}

		DB::table('users')->where('id', $id)->update($data);
	}

	//MARK: deleteUser
	public function deleteUser(int $id):void
	{
		$user_query = DB::table('users')->where('id', $id);
		$user_id    = $user_query->value('id');

		if (!$user_id) {
			throw new GeneralNotFoundException('User');
		}

		$user_query->delete();
	}
}
