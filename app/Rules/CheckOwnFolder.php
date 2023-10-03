<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Folder;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class CheckOwnFolder implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $folder = Folder::query()->where('id', $value)->first();

        if (null === $folder) {
            $fail(\trans('message.folder.invalid'));
        }

        if ($folder && $folder->user_id !== Auth::user()->id) {
            $fail(\trans('message.folder.not_own'));
        }
    }
}
