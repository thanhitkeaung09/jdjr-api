<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\DataObjects\V1\Auth\EmailRegisterData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class EmailRegisterRequest extends FormRequest implements PayloadRequestContract
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
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => \trans('message.email.unique')
        ];
    }

    public function payload(): EmailRegisterData
    {
        return EmailRegisterData::of(
            attributes: (array) $this->validated(),
        );
    }
}
