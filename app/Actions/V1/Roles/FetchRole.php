<?php

declare(strict_types=1);

namespace App\Actions\V1\Roles;

use Spatie\Permission\Models\Role;

final readonly class FetchRole
{
    public function handle(Role $role): Role
    {
        return $role->load('permissions');
    }
}
