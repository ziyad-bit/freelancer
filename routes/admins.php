<?php

use App\Http\Controllers\Admins\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.home');

//MARK:users
Route::put('/user/verify/{slug}' ,[UsersController::class,'verify'])->name('user.verify');
Route::resource('user' , UsersController::class);
