<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\{File, Password};

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
		$password_required = $this->method() === 'PUT' ? 'nullable' : 'required';

		return  [
			'name'           => 'required|string|max:50|min:3',
			'email'          => ['required', 'email', 'max:40', 'min:10', Rule::unique('users')->ignore($this->user)],
			'password' 		 => [$password_required, 'confirmed', 'string', Password::min(8),
			
				// ->mixedCase()
				// ->numbers()
				// ->symbols()
			],
		];
	}

	protected function passedValidation(): void
	{
		$this->merge(['password' => Hash::make($this->password)]);
	}
}
