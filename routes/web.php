<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\SkillController;
use App\Http\Controllers\Users\ProjectController;
use App\Http\Controllers\Users\ProposalController;
use App\Http\Controllers\Users\TransactionController;
use App\Http\Controllers\Users\VerificationController;
use App\Http\Controllers\Users\{AuthController, ChatRoomController, FileController, MessageController, NotificationsController, ProfileController, ResetPasswordController, SearchController};

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
Route::namespace('Users')->controller(AuthController::class)->group(function () {
	Route::get('/get/login'    , 'getLogin')->name('login');
	Route::post('/post/login'  , 'postLogin')->name('post.login');
	Route::post('/logout'      , 'logout')->name('logout');
	Route::get('/get/signup'   , 'create')->name('signup');
	Route::post('/post/signup' , 'store')->name('post.signup');
});

//MARK:Verification
Route::namespace('Users')->controller(VerificationController::class)->group(function () {
	Route::get('get/email/verify'           ,'get')->name('verification.get');
	Route::post('send/email/verify'         ,'send')->name('verification.send');
	Route::get('update/email/verify/{token}' ,'update')->name('verification.update');
});

//MARK:reset password
Route::namespace('Users')->controller(ResetPasswordController::class)->group(function () {
	Route::get('/reset-password/get'                ,'get')->name('reset_password.get');
	Route::post('/reset-password/send'              ,'send')->name('reset_password.send');
	Route::get('reset-password/edit/{email}/{token}','edit')->name('reset_password.edit');
	Route::post('reset-password/update'             ,'update')->name('reset_password.update');
});

//MARK:Profile
Route::namespace('Users')->controller(ProfileController::class)->group(function () {
	Route::get('/'              , 'index')->name('home');
	Route::get('/profile/delete', 'delete')->name('profile.delete');
});

Route::resource('profile'   , ProfileController::class)->except(['show','index']);

//MARK:Skill
Route::delete('/project-skill/{id}', 'Users\SkillController@destroy_project_skill')->name('project_skill.destroy');
Route::resource('skill'                  , SkillController::class)->except(['edit', 'update']);


//MARK:Project
Route::any('/project/fetch'         , 'Users\ProjectController@fetch')->name('project.fetch');
Route::resource('project'           , ProjectController::class)->except(['index']);


//MARK:file
Route::namespace('Users')->controller(FileController::class)->group(function () {
	Route::post('/file/upload'                         , 'upload')->name('file.upload');
	Route::get('/files/download/{name}/{type}/{dir}'   , 'download')->name('file.download');
	Route::delete('/files/delete/{name}/{type}/{dir}'  , 'destroy')->name('file.destroy');
});

//MARK:proposal
Route::post('proposal/update/{id}', 'Users\ProposalController@update')->name('proposal.update');
Route::resource('proposal'        , ProposalController::class)->only(['store', 'destroy']);


//MARK:message
Route::namespace('Users')->controller(MessageController::class)->group(function () {
	Route::put('message/show-old/{id}', 'show_old')->name('message.show_old');
	Route::post('message'             , 'store')->name('message.store');
	Route::get('message/{id}'         , 'show')->name('message.show');
});

//MARK:notifications
Route::namespace('Users')->controller(NotificationsController::class)->group(function () {
	Route::put('notifications/update'               , 'update')->name('notifications.update');
	Route::get('notifications/show-old/{created_at}', 'show_old')->name('notifications.show_old');
});

//MARK:chat room
Route::namespace('Users')->controller(ChatRoomController::class)->group(function () {
	Route::get('chatrooms/index'                           , 'index')->name('chatrooms.index');
	Route::get('chatrooms/fetch/{receiver_id}'             , 'fetch')->name('chatrooms.fetch');
	Route::get('chatrooms/show-more/{id}'                  , 'show_more_chat_rooms')->name('chatrooms.show_more');
	Route::get('chatrooms/users'                           , 'get_users')->name('chatrooms.get_users');
	Route::post('chatrooms/send-invitation'                , 'send_user_invitation')->name('chatrooms.send_user_invitation');
	Route::post('chatrooms/accept-invitation'              , 'post_accept_invitation')->name('chatrooms.postAcceptInvitation');
	Route::get('chatrooms/accept-invitation/{chat_room_id}', 'get_accept_invitation')->name('chatrooms.getAcceptInvitation');
	Route::post('chatrooms/refuse-invitation'              , 'refuse_invitation')->name('chatrooms.refuseInvitation');
});

//MARK:search
Route::namespace('Users')->controller(SearchController::class)->group(function () {
	Route::post('search/chatrooms'     , 'index_chatrooms')->name('search.Chatrooms');
	Route::post('search/projects'      , 'index_projects')->name('search.projects');
	Route::get('recent-search/projects', 'recent_search_projects')->name('recent_search.projects');
});

//MARK:transaction
Route::namespace('Users')->controller(TransactionController::class)->group(function () {
	Route::get('transaction/checkout/{project_id}/{receiver_id}/{amount}', 'checkout')->name('transaction.checkout');
	Route::get('transaction/milestone/create/{project_id}/{receiver_id}' , 'create')->name('transaction.milestone.create');
	Route::get('transaction/index/{created_at?}'                         , 'index')->name('transaction.index');
	Route::post('transaction/milestone/release'                          , 'release')->name('transaction.milestone.release');
	Route::get('transaction/withdraw/get'                                , 'get_withdraw')->name('transaction.get_withdraw');
	Route::post('transaction/withdraw/post'                              , 'post_withdraw')->name('transaction.post_withdraw');
});