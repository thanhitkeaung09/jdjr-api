<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\DataObjects\V1\Auth\AppleLoginData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;

final class AppleLoginRequest extends FormRequest implements PayloadRequestContract
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
                'nullable',
                'string',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'login_id' => [
                'required',
                'string',
            ],
        ];
    }

    public function payload(): AppleLoginData
    {
        return AppleLoginData::of($this->validated());
    }
}
