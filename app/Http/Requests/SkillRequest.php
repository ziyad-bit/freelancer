<?php

namespace App\Http\Requests;

use App\Rules\NotEmptyArray;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Validation\Rule;

class SkillRequest extends FormRequest
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
		$user_skills_id = DB::table('user_skill')
				->where('user_id', Auth::id())
				->pluck('skill_id')
				->toArray();

		return [
			'num_input'     => 'required|numeric',
			'skills'     	  => ['required', 'array', new NotEmptyArray],
			'skills.*.name' => 'nullable|string',
			'skills.*.id'   => ['numeric', 'distinct', 'exists:skills,id', Rule::notIn($user_skills_id)],
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
			'skills.*.id' => 'skill',
			'skills'      => 'skill',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'skills.*.id.not_in'  => 'The selected skill is added before.',
			'skills.*.id.numeric' => 'The selected skill is invalid.',
		];
	}
}
