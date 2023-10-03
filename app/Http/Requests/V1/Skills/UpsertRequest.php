<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Skills;

use App\DataObjects\V1\Skills\NewSkill;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

final class UpsertRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Skill::class)->ignore($this->skill),
            ],
            'icon' => [
                'sometimes',
                'required',
                File::image()->max(2 * 1_024 * 1_024)->types('svg'),
            ],
        ];
    }

    public function payload(): NewSkill
    {
        return NewSkill::of(
            attributes: [
                'name' => $this->string('name')->toString(),
                'icon' => $this->file('icon'),
            ],
        );
    }
}
