<?php

declare(strict_types=1);

namespace App\Actions\V1\Admins;

use App\Models\Admin;

final readonly class DeleteAdmin
{
    public function handle(Admin $admin): bool
    {
        $admin->roles()->detach();

        return $admin->delete();
    }
}
