<?php

namespace App\Interfaces\Repository;

interface NotificationRepositoryInterface
{
	public function update():void;
	public function show_old_notifs(string $created_at):string;
}
