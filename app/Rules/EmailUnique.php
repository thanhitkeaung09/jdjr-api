<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class EmailUnique implements ValidationRule
{
    public function __construct(
        public User|null $user,
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $auth = Auth::user() instanceof Admin ? $this->user : Auth::user();
        $user = User::query()
            ->whereLoginType($auth->login_type)
            ->whereLoginId(strval($value))
            ->where('id', "!=", $auth->id)
            ->get();

        if (\count($user) > 0) {
            $fail('The :attribute must be unique.');
        }
    }
}
