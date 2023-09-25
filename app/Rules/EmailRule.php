<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validated = true;

        if (!preg_match(config('regex.email_validatiton_regex'), $value)) {
            $validated = false;
        }

        if (! $validated) {
            $fail('The :attribute must be a valid email.');
        }  
    }
}
