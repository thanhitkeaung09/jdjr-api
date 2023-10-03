<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\DataObjects\V1\Auth\EmailLoginData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AdminLoginRequest extends FormRequest implements PayloadRequestContract
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
                Rule::exists(Admin::class, 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }

    public function payload(): EmailLoginData
    {
        return EmailLoginData::of(
            attributes: (array) $this->validated(),
        );
    }
}
