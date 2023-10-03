<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Categories;

use App\DataObjects\V1\Categories\NewCategory;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Category;
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
                Rule::unique(Category::class)->ignore($this->category),
            ],
            'icon' => [
                'sometimes',
                'required',
                File::image()->max(2 * 1_024 * 1_024)->types('svg'),
            ],
        ];
    }

    public function payload(): NewCategory
    {
        return NewCategory::of(
            attributes: $this->mergeIfMissing(['icon' => null])->toArray(),
        );
    }
}
