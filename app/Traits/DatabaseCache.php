<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Storage};
use Symfony\Component\HttpFoundation\StreamedResponse;

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
