<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class TransactionRequest extends FormRequest
{
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
		if (Route::currentRouteName() === route('transaction.milestone.release')) {
			$release_rules = [
				'project_id'  => 'required|numeric',
				'receiver_id' => 'required|numeric',
				'id'          => 'required|string',
			];
		}else{
			$release_rules = [];
		}

		return [
			'amount'      => 'required|numeric|min:5|max:5000',
		] + $release_rules;
	}
}
