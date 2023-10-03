<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\News;

use App\DataObjects\V1\News\NewNews;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;
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
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'short_body' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'body' => [
                'sometimes',
                'required',
                'string',
            ],
            'thumbnail' => [
                'sometimes',
                'required',
                File::image()->max(2 * 1_024 * 1024)->types(['png', 'jpg', 'jpeg']),
            ]
        ];
    }

    public function payload(): NewNews
    {
        return NewNews::of(
            attributes: [
                'title' => $this->string('title')->toString(),
                'short_body' => $this->string('short_body')->toString(),
                'body' => $this->string('body')->toString(),
                'thumbnail' => $this->file('thumbnail'),
            ],
        );
    }
}
