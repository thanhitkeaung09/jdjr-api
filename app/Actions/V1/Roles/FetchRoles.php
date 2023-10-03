<?php

declare(strict_types=1);

namespace App\Actions\V1\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final readonly class FetchRoles
{
    public function handle(?string $search = null): Builder
    {
        return Role::query()
            ->with('permissions')
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(roles.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
