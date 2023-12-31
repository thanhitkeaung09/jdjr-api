<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\LoginType;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class CheckEmailLoginUnique implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = User::query()
            ->where('email', $value)
            ->where('login_type', LoginType::GMAIL->value)
            ->exists();

        if ($exists) {
            $fail("This :attribute must be unique.");
        }
    }
}
