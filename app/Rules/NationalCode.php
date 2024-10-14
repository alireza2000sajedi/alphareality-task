<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NationalCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the input matches the pattern of 8 to 10 digits
        if (!preg_match('#^\d{8,10}$#', $value)) {
            $fail(__('validation.invalid', [
                'attribute' => $attribute,
            ]));
            return;
        }

        // Check if the value is made up of repeated single digits
        if (preg_match('#^([0-9])\1{9}$#', $value)) {
            $fail(__('validation.invalid', [
                'attribute' => $attribute,
            ]));
            return;
        }

        // Adjust value length by adding leading zeros
        $value = str_pad($value, 10, '0', STR_PAD_LEFT);

        $sum = 0;
        for ($i = 0; $i <= 8; ++$i) {
            $sum += ((int) $value[$i]) * (10 - $i);
        }

        $control = ($sum % 11) < 2 ? ($sum % 11) : (11 - ($sum % 11));

        if ((int) $value[9] !== $control) {
            $fail(__('validation.invalid', [
                'attribute' => $attribute,
            ]));
        }
    }
}

