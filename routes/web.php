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
Route::resource('project','Users\ProjectController')->except(['index']);

####################################   file   #####################################
Route::post('/file/upload','Users\FileController@upload')->name('file.upload');
Route::get('/files/{file}','Users\FileController@download')->name('file.download');
Route::delete('/files/{file}','Users\FileController@destroy')->name('file.destroy');

####################################   proposal   #####################################
Route::post('proposal/update/{id}','Users\ProposalController@update')->name('proposal.update');
Route::resource('proposal','Users\ProposalController')->only(['store','destroy']);
