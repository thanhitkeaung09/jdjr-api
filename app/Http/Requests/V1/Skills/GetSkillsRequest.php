<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Skills;

use App\DataObjects\V1\Categories\CategoryIds;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class GetSkillsRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories' => [
                'required',
                'array',
                'min:3',
                'max:5'
            ],
            'categories.*' => [
                'uuid',
                Rule::exists(Category::class, 'id'),
            ],
        ];
    }

    public function payload(): CategoryIds
    {
        return CategoryIds::of(
            attributes: $this->validated(),
        );
    }
}
