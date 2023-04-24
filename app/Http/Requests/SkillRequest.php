<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
		$skills_id = DB::table('user_skill')->where('user_id', Auth::id())->pluck('skill_id')->toArray();

		return [
			'skills_name'   => 'required|array|min:1',
			'skills_name.*' => ['distinct', 'exists:skills,id', Rule::notIn($skills_id)]
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
			'skills_name.*' => 'skill',
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
			'skills_name.*.not_in' => 'The selected skill is added before',
		];
	}
}
