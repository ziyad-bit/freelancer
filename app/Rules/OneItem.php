<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class OneItem implements InvokableRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 *
	 * @return void
	 */
	public function __invoke($attribute, $value, $fail)
	{
		if (empty(array_filter($value))) {
			$fail('You must select at least one :attribute.');
		}
	}
}
