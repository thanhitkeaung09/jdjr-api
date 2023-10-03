<?php

declare(strict_types=1);

namespace App\Actions\V1\Admins;

use App\DataObjects\V1\Admins\AdminData;
use App\Models\Admin;

final readonly class UpdateAdmin
{
    public function handle(Admin $admin, AdminData $data): bool
    {
        if ($data->roles) {
            $admin->syncRoles($data->roles);
        }

        return $admin->update($data->toArray());
    }
}
