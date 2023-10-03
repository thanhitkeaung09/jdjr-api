<?php

declare(strict_types=1);

namespace App\Actions\V1\Permissions;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

final readonly class FetchPermissions
{
    public function handle(): Builder
    {
        return Permission::query()->orderBy('name');
    }
}
