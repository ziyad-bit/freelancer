<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends FormRequest
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
		$required = $this->method() === 'PUT' ? 'nullable' : 'required';

		return [
			'location'       => 'required|string',
			'type'           => 'required|string',
			'job'            => 'required|string|max:30|min:3',
			'overview'       => 'required|string|max:250|min:3',
			'image'          => $required . '|image|mimes:jpg,gif,jpeg,png,webp|max:8000',
			'front_id_image' => [$required, File::image()->types(['jpg', 'jpeg', 'gif', 'webp', 'png'])->max(8 * 1024)],
			'back_id_image'  => [$required, File::image()->types(['jpg', 'jpeg', 'gif', 'webp', 'png'])->max(8 * 1024)],
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
			'type' => 'this field',
		];
	}
}
