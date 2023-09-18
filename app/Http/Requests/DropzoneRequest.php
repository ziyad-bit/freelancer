<?php

namespace App\Http\Requests;

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
			'image'     => 'nullable|image|mimes:jpg,gif,jpeg,png,webp|max:8000',
			'files'     => 'nullable|file|mimes:pdf,ppt,doc,xls|max:20000',
			'video'     => 'nullable|file|mimes:mp4,mov,flv,avi|max:100000',
		];
	}
}
