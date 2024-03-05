<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
	public function update() : void
	{
		User::find(Auth::id())->unreadNotifications->markAsRead();
	}

	public function show_old(string $created_at):JsonResponse
	{
		$user_notifs = User::find(Auth::id())->notifications->
		where('created_at', '<', $created_at)->take(5);

		$view = view('users.includes.notifications.index', compact('user_notifs'))->render();

		return response()->json(['view' => $view]);
	}
}
