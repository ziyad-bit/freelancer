<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ResetPasswordRequest extends FormRequest
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
			'token'    => 'required_with:password',
			'email'    => 'required|email|exists:users,email',
			'password' => 'required_with:token|min:8|confirmed',
		];
	}

	protected function passedValidation(): void
	{
		if (isset($this->password)) {
			$this->merge(['password' => Hash::make($this->password)]);
		}
	}
}
