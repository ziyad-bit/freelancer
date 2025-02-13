<?php

namespace App\Http\Controllers\Admins;

use App\Classes\User;
use App\Traits\Slug;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\RedirectResponse;

class UsersController extends Controller
{
	use Slug;

	public function __construct() 
	{
		$this->middleware('auth:admins');
	}

    // MARK: index   
	public function index():View
	{
		$users = DB::table('users')
			->select('id','name','slug')
			->paginate(10);

		return view('admins.user.index',compact('users'));
	}

    // MARK: create   
	public function create():View
	{
		return view('admins.user.create');
	}

    // MARK: store   
    public function store(SignupRequest $request):RedirectResponse
	{
		$slug = $this->createSlug('users', 'name', $request->name);

		$data = $request->safe()->except('password') +
			[
				'password'   => $request->password,
				'created_at' => now(),
				'slug'       => $slug,
			];

		DB::table('users')->insert($data);

		return to_route('admin.user.create')->with('success','User is created successfully');
	}

    // MARK: show   
	public function show(string $slug):View
	{
		$user = User::index($slug);

		return view('admins.user.show',compact('user'));
	}

    // MARK: edit   
	public function edit(int $id):View
	{
		$user = DB::table('users')->where('id',$id)->first();

		return view('admins.user.edit',compact('user'));
	}

    // MARK: update   
	public function update( $request , int $id):RedirectResponse
	{
		return to_route('');
	}

    // MARK: destroy   
	public function destroy(int $id):RedirectResponse
	{
		return to_route('');
	}
}
