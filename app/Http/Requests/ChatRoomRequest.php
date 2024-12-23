<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRoomRequest extends FormRequest
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
		$is_user_id_required = request()->routeIs('chatrooms.send_user_invitation') ? 'required' : 'nullable';

		return [
			'chat_room_id' => 'required|uuid',
			'user_id'      => $is_user_id_required . '|numeric',
		];
	}
}
