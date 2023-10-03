<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\DataObjects\V1\Auth\SocialLoginData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Rules\CheckEmailLoginUnique;
use Illuminate\Foundation\Http\FormRequest;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final class SocialLoginRequest extends FormRequest implements PayloadRequestContract
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
                'required_without:phone',
                'nullable',
                'string',
                'email',
                'max:255',
                new CheckEmailLoginUnique(),
            ],
            'phone' => [
                'required_without:email',
                'nullable',
                'string',
                'max:255',
            ],
            'login_id' => [
                'required',
                'string',
                'max:255',
            ],
            'profile' => [
                'nullable',
                'url',
                'active_url',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => \trans('message.email.unique'),
        ];
    }

    public function payload(): DataObjectContract
    {
        return SocialLoginData::of(
            attributes: $this->safe()->merge([
                'login_type' => $this->type
            ])->toArray(),
        );
    }
}
