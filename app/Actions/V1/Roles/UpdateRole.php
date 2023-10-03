<?php

declare(strict_types=1);

namespace App\Actions\V1\Roles;

use App\DataObjects\V1\Roles\NewRoleData;
use Spatie\Permission\Models\Role;

final readonly class UpdateRole
{
    public function handle(Role $role, NewRoleData $data): bool
    {
        if ($data->permissions->count() > 0) {
            $role->permissions()->sync($data->permissions);
        }

        return $role->update($data->toArray());
    }
}
