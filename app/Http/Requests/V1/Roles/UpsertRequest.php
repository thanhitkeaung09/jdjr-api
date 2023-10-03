<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Roles;

use App\DataObjects\V1\Roles\NewRoleData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
                Rule::unique(Role::class, 'name')->ignore($this->role),
            ],
            'permissions' => [
                'sometimes',
                'required',
                'array',
            ],
            'permissions.*' => [
                'integer',
                Rule::exists(Permission::class, 'id'),
            ],
        ];
    }

    public function payload(): NewRoleData
    {
        return NewRoleData::of(
            attributes: $this->validated(),
        );
    }
}
