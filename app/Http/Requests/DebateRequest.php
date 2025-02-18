<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DebateRequest extends FormRequest
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
			'description'     => ['required', 'string', 'max:500', 'min:10'],
			'project_id'      => ['required', 'numeric'],
			'opponent_id'         => ['required', 'numeric'],
		];
	}
}
