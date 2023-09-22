<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validated = true;

        if (!preg_match(config('regex.name_validation_regex'), $value)) {
            $validated = false;
        }

        if (! $validated) {
            $fail('The :attribute can only contain letters (uppercase or lowercase) and space.');
        }        
    }
}
