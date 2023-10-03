<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Otps;

use App\DataObjects\V1\Otps\ConfirmData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final class ConfirmRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::exists(User::class, 'email'),
            ],
            'otp' => [
                'required',
                'string',
                'size:6',
                Rule::when(
                    condition: 'production' === \config('app.env'),
                    rules: Rule::exists(Otp::class, 'otp')
                ),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => \trans('message.email.not_register'),
        ];
    }

    public function payload(): DataObjectContract
    {
        return ConfirmData::of(
            attributes: (array) $this->validated(),
        );
    }
}
