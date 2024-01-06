<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
			'chat_room_id' => 'required|numeric',
			'receiver_id'  => 'required|numeric',
			'text'         => 'required|string|max:250',
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
