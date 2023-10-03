<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Categories;

use App\DataObjects\V1\Categories\NewSubcategory;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Subcategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpsertSubcategoryRequest extends FormRequest implements PayloadRequestContract
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
                Rule::unique(Subcategory::class)->ignore($this->subcategory),
            ]
        ];
    }

    public function payload(): NewSubcategory
    {
        return NewSubcategory::of(
            attributes: [
                'name' => $this->string('name')->toString(),
                'category_id' => $this->category,
            ],
        );
    }
}
