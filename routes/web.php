<?php

use App\Http\Controllers\Users\ChatRoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\FileController;
use App\Http\Controllers\Users\MessageController;
use App\Http\Controllers\Users\NotificationsController;
use App\Http\Controllers\Users\SearchController;

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

//MARK:Auth
Route::namespace('Users')->controller('AuthController')->group(function(){
    Route::get ('/login'   ,'getLogin')->name('login');
    Route::post('/login'   ,'postLogin')->name('post.login');
    Route::get ('/'        ,'index')->name('home');
    Route::post('/logout'  ,'logout')->name('logout');
    Route::get ('/signup'  ,'create')->name('signup');
    Route::post('/signup'  ,'store')->name('post.signup');
});

//MARK:Profile
Route::get('/profile/delete','Users\ProfileController@delete')->name('profile.delete');
Route::resource('profile'   ,'Users\ProfileController')->except(['show']);


//MARK:Skill
Route::delete('/project-skill/{skill_id}','Users\SkillController@destroy_project_skill')->name('project_skill.destroy');
Route::resource('skill'                  ,'Users\SkillController')->except(['show','edit','update']);


//MARK:Project
Route::any('/project/fetch-projects','Users\ProjectController@fetch_projects')->name('project.fetch');
Route::resource('project'           ,'Users\ProjectController')->except(['index']);


//MARK:file
Route::namespace('Users')->controller(FileController::class)->group(function(){
    Route::post  ('/file/upload'    ,'upload')->name('file.upload');
    Route::get   ('/files/{file}'   ,'download')->name('file.download');
    Route::delete('/files/{file}'   ,'destroy')->name('file.destroy');
});

//MARK:proposal
Route::post('proposal/update/{id}','Users\ProposalController@update')->name('proposal.update');
Route::resource('proposal'        ,'Users\ProposalController')->only(['store','destroy']);


//MARK:message
Route::namespace('Users')->controller(MessageController::class)->group(function(){
    Route::put ('message/show-old/{id}'   ,'show_old')->name('message.show_old');
    Route::post('message'                 ,'store')->name('message.store');
    Route::get ('message/{id}'            ,'show')->name('message.show');
});

//MARK:notifications
Route::namespace('Users')->controller(NotificationsController::class)->group(function(){
    Route::put ('notifications/update'          ,'update')->name('notifications.update');
    Route::get ('notifications/show-old/{created_at}'   ,'show_old')->name('notifications.show_old');
});

//MARK:chat room
Route::namespace('Users')->controller(ChatRoomController::class)->group(function(){
    Route::get ('chat-room/index'                           ,'index')->name('chat-rooms.index');
    Route::get ('chat-room/fetch/{receiver_id}'             ,'fetch')->name('chat-rooms.fetch');
    Route::get ('chat-room/show-more/{id}'                  ,'show_more_chat_rooms')->name('message.show_chat_rooms');
    Route::post('chat-room/accept-invitation'               ,'post_accept_invitation')->name('chat-rooms.postAcceptInvitation');
    Route::get ('chat-room/accept-invitation/{chat_room_id}','get_accept_invitation')->name('chat-rooms.getAcceptInvitation');
    Route::post('chat-room/refuse-invitation'               ,'refuse_invitation')->name('chat-rooms.refuseInvitation');
    Route::post('chat-room/send-invitation'                 ,'send_user_invitation')->name('chat-room.send_user_invitation');
});

//MARK:search
Route::namespace('Users')->controller(SearchController::class)->group(function(){
    Route::post('search/chat-room'        ,'index_chatrooms')->name('search.Chatrooms');
    Route::post('search/projects'         ,'index_projects')->name('search.projects');
    Route::get ('recent-search/projects'  ,'recent_search_projects')->name('recent_search.projects');
});
