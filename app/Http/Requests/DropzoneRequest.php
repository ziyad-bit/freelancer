<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class DropzoneRequest extends FormRequest
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
			'image'           => ['nullable',File::image()->types(['jpg', 'jpeg','gif','webp','png'])->max(12 * 1024)] ,
			'application'     => ['nullable',File::types(['pdf', 'ppt','doc','xls'])->max(20 * 1024)],
			'video'           => ['nullable',File::types(['mp4', 'mov','flv','avi'])->max(150 * 1024)],
			'dir'             => ['required', 'string', Rule::in(['projects/', 'messages/'])],
			'type'            => ['required', 'string', Rule::in(['image', 'video', 'application'])],
		];
	}
}
