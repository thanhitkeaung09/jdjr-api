<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Experiences;

use App\DataObjects\V1\Experiences\NewExperience;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
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
            'duration' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('experiences', 'duration')->ignore($this->experience),
            ],
            'level' => [
                'sometimes',
                'required',
                'uuid',
                Rule::exists('levels', 'id'),
            ],
        ];
    }

    public function payload(): NewExperience
    {
        return NewExperience::of(
            attributes: [
                'duration' => $this->string('duration')->toString(),
                'level' => $this->string('level')->toString(),
            ],
        );
    }
}
