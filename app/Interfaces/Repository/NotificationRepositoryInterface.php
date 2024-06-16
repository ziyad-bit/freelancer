<?php

namespace App\Interfaces\Repository;

use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
	public function update():void;
	public function show_old_notifs(string $created_at):string;
}
