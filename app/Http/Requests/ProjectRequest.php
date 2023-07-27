<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
			'title'         => 'required|string|max:50|min:5',
			'content'       => 'required|string|max:250|min:10',
			'num_of_days'   => 'required|numeric|max:180|min:1',
			'min_price'     => 'required|numeric|lt:max_price|min:5',
			'max_price'     => 'required|numeric|max:10000|gt:min_price',
			'exp'           => ['required', 'string', Rule::in(['beginer', 'intermediate', 'experienced'])],
			'skills_name'   => 'required|array|min:1',
			'skill_id.*'    => ['distinct', 'exists:skills,id'],
			'num_input'     => 'required|numeric',
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
			'min_price'     => 'minimum price',
			'max_price'     => 'maximum price',
			'exp'           => 'experience',
			'skill_id.*'    => 'skill',
		];
	}
}
