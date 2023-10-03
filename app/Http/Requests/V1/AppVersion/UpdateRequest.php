<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\AppVersion;

use App\DataObjects\V1\AppVersions\AppVersionData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'version' => [
                'required',
                'string',
                'max:255',
            ],
            'build_no' => [
                'required',
                'string',
                'max:255',
            ],
            'is_forced_updated' => [
                'required',
                'boolean',
            ],
            'ios_link' => [
                'required',
                'string',
                'max:255',
            ],
            'android_link' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function payload(): AppVersionData
    {
        return AppVersionData::of(
            attributes: [
                ...$this->validated(),
                'is_forced_updated' => $this->boolean('is_forced_updated'),
            ],
        );
    }
}
