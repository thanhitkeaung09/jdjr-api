<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Levels;

use App\DataObjects\V1\Levels\NewLevel;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Level;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                Rule::unique(Level::class)->ignore($this->level),
            ],
        ];
    }

    public function payload(): NewLevel
    {
        return NewLevel::of(
            attributes: [
                'name' => $this->string('name')->toString(),
            ],
        );
    }
}
