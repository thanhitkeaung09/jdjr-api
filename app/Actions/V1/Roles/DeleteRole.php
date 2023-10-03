<?php

declare(strict_types=1);

namespace App\Actions\V1\Roles;

use Spatie\Permission\Models\Role;

final readonly class DeleteRole
{
    public function handle(Role $role): bool
    {
        $role->permissions()->detach();

        return $role->delete();
    }
}
