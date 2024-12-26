<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Interfaces\Repository\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;

class NotificationsController extends Controller
{
	public function __construct(private NotificationRepositoryInterface $notificationsRepository)
	{
		$this->middleware(['auth','verifyEmail']);
	}

	//MARK: update
	public function update() : JsonResponse
	{
		$this->notificationsRepository->update();

		return response()->json();
	}

	//MARK: show_old
	public function show_old(string $created_at):JsonResponse
	{
		$view = $this->notificationsRepository->show_old_notifs($created_at);

		return response()->json(['view' => $view]);
	}
}
