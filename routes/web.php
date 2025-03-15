<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\SkillController;
use App\Http\Controllers\Users\ProjectController;
use App\Http\Controllers\Users\ProposalController;
use App\Http\Controllers\Users\AuthProjectController;
use App\Http\Controllers\Users\TransactionController;
use App\Http\Controllers\Users\VerificationController;
use App\Http\Controllers\Users\{AuthController, ChatRoomController, ChatRoomInvitationController, FileController, MessageController, NotificationsController, ProfileController, ResetPasswordController, SearchController, SocialiteController};

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
	Route::get('/get/login'      	  , 'getLogin')->name('user.login');
	Route::post('/post/login'         , 'postLogin')->name('post.user.login');
	Route::post('/logout'             , 'logout')->name('user.logout');
	Route::get('/get/signup'          , 'create')->name('signup');
	Route::post('/post/signup'        , 'store')->name('post.signup');
});

//MARK:email Verify
Route::namespace('Users')->controller(VerificationController::class)->group(function () {
	Route::get('get/email/verify'            ,'get')->name('verification.get');
	Route::post('send/email/verify'          ,'send')->name('verification.send');
	Route::get('update/email/verify/{token}' ,'update')->name('verification.update');
});

//MARK:reset password
Route::namespace('Users')->controller(ResetPasswordController::class)->group(function () {
	Route::get('/reset-password/get'                ,'get')->name('reset_password.get');
	Route::post('/reset-password/send'              ,'send')->name('reset_password.send');
	Route::get('reset-password/edit/{email}/{token}','edit')->name('reset_password.edit');
	Route::post('reset-password/update'             ,'update')->name('reset_password.update');
});

//MARK:socialite
Route::namespace('Users')->controller(SocialiteController::class)->group(function () {
	Route::get('auth/redirect/{provider}', 'redirect')->name('auth.redirect');
	Route::get('auth/callback/{provider}', 'callback')->name('auth.callback');
});



//MARK:Profile
Route::namespace('Users')->controller(ProfileController::class)->group(function () {
	Route::get('/profile/get/{slug}', 'get')->name('profile.get');
	Route::get('/profile/delete', 'delete')->name('profile.delete');
});

Route::resource('profile' , ProfileController::class)->except(['show','index']);

//MARK:Skill
Route::delete('/project-skill/{id}' ,[SkillController::class,'destroy_project_skill'])->name('project_skill.destroy');
Route::resource('skill'             , SkillController::class)->except(['edit', 'update']);


//MARK:find Project
Route::any('/'             ,[ProjectController::class,'fetch'])->name('home');
Route::resource('project'  , ProjectController::class)->except(['index']);


//MARK:auth Project
Route::namespace('Users')->controller(AuthProjectController::class)->group(function () {
	Route::get('/auth/project/index'                                 , 'index')->name('auth.project.index');
	Route::get('/auth/project/debate/create/{project_id}/{user_id}'  , 'debate_create')->name('auth.project.debate_create');
	Route::post('/auth/project/debate/store'                         , 'debate_store')->name('auth.project.debate_store');
});


//MARK:file
Route::namespace('Users')->controller(FileController::class)->group(function () {
	Route::post('/file/upload'                         , 'upload')->name('file.upload');
	Route::get('/files/download/{name}/{type}/{dir}'   , 'download')->name('file.download');
	Route::delete('/files/delete/{name}/{type}/{dir}'  , 'destroy')->name('file.destroy');
});

//MARK:proposal
Route::resource('proposal' , ProposalController::class)->only(['store', 'destroy','update','show']);




//MARK:message
Route::namespace('Users')->controller(MessageController::class)->group(function () {
	Route::post('message'                            , 'store')->name('message.store');
	Route::get('message/{chat_room_id}/{created_at?}' , 'show')->name('message.show');
});

//MARK:notifications
Route::namespace('Users')->controller(NotificationsController::class)->group(function () {
	Route::put('notifications/update'               , 'update')->name('notifications.update');
	Route::get('notifications/show-old/{created_at}', 'show_old')->name('notifications.show_old');
});

//MARK:chat room
Route::namespace('Users')->controller(ChatRoomController::class)->group(function () {
	Route::get('chatrooms/index'               , 'index')->name('chatrooms.index');
	Route::get('chatrooms/fetch/{receiver_id}' , 'fetch')->name('chatrooms.fetch');
	Route::get('chatrooms/show-more/{id}'      , 'show_more_chat_rooms')->name('chatrooms.show_more');
});

//MARK:chatroomInvitation
Route::namespace('Users')->controller(ChatRoomInvitationController::class)->group(function () {
	Route::get('chatrooms/users/{chat_room_id}'            , 'get_users')->name('chatrooms.get_users');
	Route::post('chatrooms/send-invitation'                , 'send_user_invitation')->name('chatrooms.send_user_invitation');
	Route::post('chatrooms/accept-invitation'              , 'accept_invitation')->name('chatrooms.postAcceptInvitation');
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
