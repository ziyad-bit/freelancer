<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
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
		$route = Route::currentRouteName();

		return [
			'name'      => $route == 'login' ? 'nullable' : 'required' . '|string|max:50|min:3',
			'email'     => $route == 'login' ? '' : 'unique:users,email' . '|required|email|max:50|min:10|',
			'password'  => $route == 'signup' ? 'confirmed' : '' . 'required|string|max:50|min:8',
		];
	}
}
