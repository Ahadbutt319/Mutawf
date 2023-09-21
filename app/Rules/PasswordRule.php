<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validated = true;

        // Check for minimum length (e.g., 8 characters)
        if (strlen($value) < 8) {
            $validated = false;
        }

        // Check for at least one uppercase letter
        if (!preg_match(config('regex.block_alphabets'), $value)) {
            $validated = false;
        }

        // Check for at least one lowercase letter
        if (!preg_match(config('regex.small_alphabets'), $value)) {
            $validated = false;
        }

        // Check for at least one number
        if (!preg_match(config('regex.numeric_characters'), $value)) {
            $validated = false;
        }

        // Check for at least one special character
        if (!preg_match(config('regex.special_characters'), $value)) {
            $validated = false;
        }

        if (! $validated) {
            $fail('The :attribute must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
        }
    }
}
