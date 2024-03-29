<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\NotificationRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
	public function __construct(private NotificationRepositoryInterface $notificationsRepository)
	{
		$this->middleware('auth');
	}

	####################################   update   #####################################
	public function update() : void
	{
		$this->notificationsRepository->update();
	}

	####################################   show_old   #####################################
	public function show_old(string $created_at):JsonResponse
	{
		$view = $this->notificationsRepository->show_old_notifs($created_at);

		return response()->json(['view' => $view]);
	}
}
