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
			'chat_room_id' => 'required|uuid',
			'receiver_id'  => 'required|numeric',
			'text'         => 'required_without:files|nullable|string|max:500',
			'files.*.name' => 'nullable|string',
			'files.*.type' => 'nullable|string',
		];
	}

	/**
	 * Handle a passed validation attempt.
	 *
	 * @return void
	 */
	protected function passedValidation()
	{
		$this->merge(['text' => encrypt($this->text)]);
	}
}
