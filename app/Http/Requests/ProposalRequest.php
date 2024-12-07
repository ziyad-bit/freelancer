<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ProposalRequest extends FormRequest
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
		return [
			'content'       => 'required|string|max:250|min:10',
			'num_of_days'   => 'required|numeric|max:180|min:1',
			'price'         => 'required|numeric|max:8000|min:5',
			'project_id'    => request()->routeIs('proposal.update') ? 'required' : '' . '|numeric',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'num_of_days'   => 'number of days',
		];
	}
}
