<?php

declare(strict_types=1);

namespace App\Actions\V1\Admins;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

final readonly class FetchAdmin
{
    public function handle(Admin|Authenticatable $admin): Admin|Model
    {
        return $admin->load(['roles', 'roles.permissions']);
    }
}
