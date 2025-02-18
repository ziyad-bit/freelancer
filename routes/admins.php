<?php

use App\Http\Controllers\Admins\{AdminsController, DebatesController, ProjectsController, UsersController};
use Illuminate\Support\Facades\{Auth, Route};

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.home');

//MARK:users
Route::put('/user/verify/{slug}', [UsersController::class, 'verify'])->name('user.verify');
Route::resource('user', UsersController::class);

//MARK:admins
Route::resource('admin', AdminsController::class)
	->names([
		'index'   => 'index',
		'create'  => 'create',
		'store'   => 'store',
		'show'    => 'show',
		'edit'    => 'edit',
		'update'  => 'update',
		'destroy' => 'destroy',
	]);


//MARK:projects
Route::put('/project/active/{id}', [ProjectsController::class, 'active'])->name('project.active');
Route::resource('project', ProjectsController::class);

//MARK:debates
Route::resource('debate', DebatesController::class);
