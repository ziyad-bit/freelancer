<?php

namespace App\Traits;

use Illuminate\Support\Facades\{Auth, DB};

trait GetFunds
{
	//MARK: forgetCache
	public function get_total_money():int
	{
		$auth_id = Auth::id();
		$earn    = DB::table('transactions')
			->select(DB::raw('SUM(amount) as total_earn'))
			->where('type', 'release')
			->where('receiver_id', Auth::id())
			->value('total_earn');

		$withdraw = DB::table('transactions')
			->select(DB::raw('SUM(amount) as total_withdraw'))
			->where('type', 'withdraw')
			->where('owner_id', $auth_id)
			->value('total_withdraw');

		return $earn - $withdraw;
	}
}
