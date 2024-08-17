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
			'*.files'=> 'nullable|string',
			'all_images_count' => 'nullable|numeric',
			'all_videos_count'         => 'nullable|numeric',
			'all_apps_count'         => 'nullable|numeric',
		];
	}
}
