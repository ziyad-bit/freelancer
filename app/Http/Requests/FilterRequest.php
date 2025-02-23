<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
			'num_of_days' => 'nullable|numeric||max:90',
			'min_price' => 'nullable|numeric|min:5|lte:max_price',
			'max_price' => 'nullable|numeric|max:10000',
			'exp.*' => 'nullable|string|in:beginner,intermediate,experienced',
			'search' => 'nullable|string|max:30',
		];
	}
}
