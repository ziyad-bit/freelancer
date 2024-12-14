<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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
			'num_input' => 'required|numeric',
    'skills_id' => [
        'required',
        'array',
        function ($attribute, $value, $fail) {
            // Ensure at least one non-null item exists in the array
            if (empty(array_filter($value))) {
                $fail('The :attribute must contain at least one valid skill.');
            }
        },
    ],
    'skills_name.*' => 'nullable',
    'skills_id.*' => ['nullable', 'distinct', 'exists:skills,id', Rule::notIn($user_skills_id)],
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
			'skills_id.*' => 'skill',
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
			'skills_name.*.not_in'   => 'The selected skill is added before',
		];
	}
}
