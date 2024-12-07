<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
		return  [
			'name'      => 'required|string|max:20|min:3',
			'email'     => 'unique:users,email|required|email|max:40|min:10|',
			'password'  => 'confirmed|required|string|max:30|min:8',
		];
	}
}
