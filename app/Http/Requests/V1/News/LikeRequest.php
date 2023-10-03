<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\News;

use App\DataObjects\V1\News\LikeData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;

final class LikeRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'like' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function payload(): LikeData
    {
        return LikeData::of(
            attributes: [
                'user' => $this->user(),
                'like' => $this->boolean('like'),
            ]
        );
    }
}
