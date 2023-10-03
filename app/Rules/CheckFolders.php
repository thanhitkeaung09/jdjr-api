<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Folder;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class CheckFolders implements ValidationRule
{
    public function __construct(
        public Folder|null $folder,
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $folders = Folder::query()->where('user_id', Auth::user()->id)->get();

        $sameFolders = $folders->reject(fn ($f) => $f->id === $this->folder?->id);

        if ($sameFolders->count() >= 5) {
            $fail(\trans('message.validation.count_gt_5'));
        }

        if ($sameFolders->where('name', $value)->count() > 0) {
            $fail(\trans('message.validation.unique'));
        }
    }
}
