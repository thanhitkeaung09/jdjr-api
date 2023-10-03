<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Tools;

use App\DataObjects\V1\Tools\NewTool;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Tool;
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
                Rule::unique(Tool::class)->ignore($this->tool),
            ],
            'icon' => [
                'sometimes',
                'required',
                File::image()->max(2 * 1_024 * 1_024)->types('svg'),
            ],
        ];
    }

    public function payload(): NewTool
    {
        return NewTool::of(
            attributes: [
                'name' => $this->string('name')->toString(),
                'icon' => $this->file('icon'),
            ],
        );
    }
}
