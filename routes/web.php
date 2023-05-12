<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

####################################   Auth   #####################################
Route::namespace('Users')->controller('AuthController')->group(function(){
    Route::get('/login','getLogin')->name('login');
    Route::post('/login','postLogin')->name('login');
    Route::get('/','index')->name('home');
    Route::post('/logout','logout')->name('logout');
    Route::get('/signup','create')->name('signup');
    Route::post('/signup','store')->name('signup');
});

####################################   Profile   #####################################
Route::get('/profile/delete','Users\ProfileController@delete')->name('profile.delete');
Route::resource('profile','Users\ProfileController')->except(['show']);

####################################   Skill   #####################################
Route::resource('skill','Users\SkillController')->except(['show','edit','update']);

####################################   Project   #####################################
Route::any('/project/index-projects','Users\ProjectController@index_projects')->name('project.index_posts');
Route::resource('project','Users\ProjectController')->except(['show','index']);
