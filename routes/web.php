<?php

use App\Http\Controllers\Users\ChatRoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\FileController;
use App\Http\Controllers\Users\MessageController;
use App\Http\Controllers\Users\NotificationsController;

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
    Route::get ('/login'   ,'getLogin')->name('login');
    Route::post('/login'   ,'postLogin')->name('post.login');
    Route::get ('/'        ,'index')->name('home');
    Route::post('/logout'  ,'logout')->name('logout');
    Route::get ('/signup'  ,'create')->name('signup');
    Route::post('/signup'  ,'store')->name('post.signup');
});

####################################   Profile   #####################################
Route::get('/profile/delete','Users\ProfileController@delete')->name('profile.delete');
Route::resource('profile'   ,'Users\ProfileController')->except(['show']);

####################################   Skill   #####################################
Route::delete('/project-skill/{skill_id}','Users\SkillController@destroy_project_skill')->name('project_skill.destroy');
Route::resource('skill'                  ,'Users\SkillController')->except(['show','edit','update']);

####################################   Project   #####################################
Route::any('/project/index-projects','Users\ProjectController@index_projects')->name('project.index_posts');
Route::resource('project'           ,'Users\ProjectController')->except(['index']);

####################################   file    #####################################
Route::namespace('Users')->controller(FileController::class)->group(function(){
    Route::post  ('/file/upload'    ,'upload')->name('file.upload');
    Route::get   ('/files/{file}'   ,'download')->name('file.download');
    Route::delete('/files/{file}'   ,'destroy')->name('file.destroy');
});

####################################   proposal   #####################################
Route::post('proposal/update/{id}','Users\ProposalController@update')->name('proposal.update');
Route::resource('proposal'        ,'Users\ProposalController')->only(['store','destroy']);

####################################   message   #####################################
Route::namespace('Users')->controller(MessageController::class)->group(function(){
    Route::get ('message/index/{id?}'     ,'index_chatrooms')->name('chat-rooms.index');
    Route::put ('message/show-old/{id}'   ,'show_old')->name('message.show_old');
    Route::post('message'                 ,'store')->name('message.store');
    Route::get ('message/{id}'            ,'show')->name('message.show');
    Route::get ('message/chat-rooms/{id}' ,'show_chat_rooms')->name('message.show_chat_rooms');
});

####################################   notifications   #####################################
Route::namespace('Users')->controller(NotificationsController::class)->group(function(){
    Route::put ('notifications/update'          ,'update')->name('notifications.update');
    Route::get ('notifications/show-old/{id}'   ,'show_old')->name('notifications.show_old');
});

####################################   chat room   #####################################
Route::namespace('Users')->controller(ChatRoomController::class)->group(function(){
    Route::post('chat-room/add-user/{receiver_id}/{chat_room_id}'        ,'add_user')->name('chat-room.add_user');
    Route::get ('chat-room/show-old/{id}'   ,'show_old')->name('chat-room.show_old');
});
