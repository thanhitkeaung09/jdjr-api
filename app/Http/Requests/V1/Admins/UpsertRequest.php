<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admins;

use App\DataObjects\V1\Admins\AdminData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

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
                'max:255'
            ],

            'email' => [
                'sometimes',
                'required',
                'string',
                'email:strict',
                'max:255',
                Rule::unique(Admin::class, 'email')->ignore($this->admin),
            ],

            'password' => [
                'sometimes',
                'required',
                'string',
                'confirmed',
                'min:8',
                'max:255',
            ],

            'roles' => [
                'sometimes',
                'required',
                'array',
            ],

            'roles.0' => [
                'integer',
                Rule::exists(Role::class, 'id'),
            ],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->admin) {
            $this->mergeIfMissing($this->admin->toArray());
        }
    }

    public function payload(): DataObjectContract
    {
        return AdminData::of(
            attributes: $this->toArray(),
        );
    }
}
