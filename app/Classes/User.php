<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class User
{
	public static function index(string $slug):stdClass
	{
		return DB::table('users')
				->select(
					'users.id',
					'users.name',
					'email',
					'users.created_at',
					'image',
					'slug',
					'email_verified_at',
					'location',
					'job',
					'overview',
					DB::raw('Group_concat(Distinct Concat(skill,":",user_skill.id) ) as skills'),
					DB::raw('ROUND(AVG(rate), 1) as review'),
					DB::raw('IFNULL(transaction_earn.total_amount, 0) as total_amount'),
					DB::raw('IFNULL(transaction_withdraw.total_withdraw, 0) as total_withdraw')
				)
				->leftJoin('user_skill', 'users.id', '=', 'user_skill.user_id')
				->leftJoin('skills', 'skills.id', '=', 'user_skill.skill_id')
				->leftJoin('user_infos', 'user_infos.user_id', '=', 'users.id')
				->leftJoin('reviews', 'reviews.receiver_id', '=', 'users.id')
				->leftJoin(
					DB::raw(
						'(
						SELECT receiver_id, SUM(amount) as total_amount 
						FROM transactions 
						WHERE type = "release" 
						GROUP BY receiver_id
						) as transaction_earn'
					),
					'transaction_earn.receiver_id',
					'=',
					'users.id'
				)
				->leftJoin(
					DB::raw(
						'(
						SELECT owner_id, SUM(amount) as total_withdraw
						FROM transactions 
						WHERE type = "withdraw" 
						GROUP BY owner_id
						) as transaction_withdraw'
					),
					'transaction_withdraw.owner_id',
					'=',
					'users.id'
				)
				->where('users.slug',Auth::user()->slug)
				->groupBy('users.id')
				->first();
	}
}
