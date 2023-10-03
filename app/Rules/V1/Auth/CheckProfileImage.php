<?php

declare(strict_types=1);

namespace App\Rules\V1\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class CheckProfileImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( ! ($value instanceof UploadedFile) && ! is_string($value)) {
            $fail('The :attribute must be string or file.');
        }
    }
}
