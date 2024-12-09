<?php

namespace App\Http\Requests;

use App\Traits\GetFunds;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class TransactionRequest extends FormRequest
{
	use GetFunds;
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		$release_rules = [];

		if (request()->routeIs('transaction.milestone.release')) {
			$release_rules = [
				'project_id'  => 'required|numeric',
				'receiver_id' => 'required|numeric',
				'id'          => 'required|string',
			];
		} 

		$user_funds = $this->get_total_money();

		return [
			'amount'      => 'required|numeric|min:5|max:' . $user_funds,
		] + $release_rules;
	}
}
