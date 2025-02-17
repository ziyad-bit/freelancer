<?php

namespace App\Repositories\Admins;

use App\Classes\Admin;
use App\Exceptions\GeneralNotFoundException;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\SignupRequest;
use App\Interfaces\Repository\Admins\AdminRepositoryInterface;
use App\Interfaces\Repository\ProfileRepositoryInterface;
use App\Traits\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use stdClass;

class AdminRepository implements AdminRepositoryInterface
{
	//MARK: getAdmin
	public function indexAdmin():LengthAwarePaginator
	{
		return DB::table('admins')
			->select('id', 'name', 'created_at')
			->latest('id')
			->paginate(10);
	}

	//MARK: storeAdmin
	public function storeAdmin(SignupRequest $request):void
	{
		$data = $request->safe()->except('password') +
			[
				'password'   => $request->password,
				'created_at' => now(),
			];

		DB::table('admins')->insert($data);
	}

	//MARK: showAdmin
	public function showAdmin(int $id):stdClass
	{
		$admin_query = DB::table('admins')->where('id', $id);

		if (!$admin_query->exists()) {
			throw new GeneralNotFoundException('Admin');
		}

		return DB::table('admins')->where('id',$id)->first();
	}

	//MARK: editAdmin
	public function editAdmin(int $id):stdClass
	{
		$admin_query = DB::table('admins')->where('id', $id);
		$admin_id    = $admin_query->value('id');

		if (!$admin_id) {
			throw new GeneralNotFoundException('Admin');
		}

		return $admin_query->first();
	}

	//MARK: updateAdmin
	public function updateAdmin(SignupRequest $request,int $id):void
	{
		$admin_query = DB::table('admins')->where('id', $id);
		$admin_id    = $admin_query->value('id');

		if (!$admin_id) {
			throw new GeneralNotFoundException('Admin');
		}

		if ($request->has('password')) {
			$data = $request->safe()->except('password') + ['password' => $request->password];
		} else {
			$data = $request->validated() + ['updated_at' => now()];
		}

		DB::table('admins')->where('id', $id)->update($data);
	}

	//MARK: deleteAdmin
	public function deleteAdmin(int $id):void
	{
		$admin_query = DB::table('admins')->where('id', $id);
		$admin_id    = $admin_query->value('id');

		if (!$admin_id) {
			throw new GeneralNotFoundException('Admin');
		}

		$admin_query->delete();
	}
}
