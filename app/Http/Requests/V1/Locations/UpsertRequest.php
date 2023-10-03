<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Locations;

use App\DataObjects\V1\Locations\NewLocation;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique(Location::class, 'name')->ignore($this->location),
            ]
        ];
    }

    public function payload(): NewLocation
    {
        return NewLocation::of(
            attributes: [
                'name' => $this->string('name')->toString(),
            ]
        );
    }
}
