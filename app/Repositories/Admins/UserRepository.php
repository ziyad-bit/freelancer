<?php

namespace App\Repositories\Admins;

use App\Classes\User;
use App\Classes\Users;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserRepository implements UserRepositoryInterface
{
	//MARK: getUser
	public function indexUser():LengthAwarePaginator
	{
		return DB::table('users')
			->select('id', 'name', 'slug')
			->latest('id')
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

	//MARK: showUser
	public function showUser(string $slug):stdClass
	{
		$user_query = DB::table('users')->where('slug', $slug);

		if (!$user_query->exists()) {
			throw new GeneralNotFoundException('User');
		}

		return Users::index($slug);
	}

	//MARK: verifyUser
	public function verifyUser(string $slug):void
	{
		$user_query = DB::table('users')->where('slug', $slug);
		$user       = $user_query->lockForUpdate()->first();

		if (!$user_query) {
			throw new GeneralNotFoundException('User');
		}

		$user_query->update(['profile_verified_at' => now()]);
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
	public function updateUser(SignupRequest $request, int $id):void
	{
		$user_query = DB::table('users')->where('id', $id);
		$user_id    = $user_query->lockForUpdate()->value('id');

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
