<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait DatabaseCache
{
	//MARK: forgetCache
	public function forgetCache(int $user_id):void
	{
		if (Cache::has('notifs_' . $user_id)) {
			Cache::forget('notifs_' . $user_id);
			Cache::increment('notifs_count_' . $user_id);
		}
	}
}
