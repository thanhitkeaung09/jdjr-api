<?php

declare(strict_types=1);

namespace App\Actions\V1\Admins;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchAdmins
{
    public function handle(?string $search = null): Builder
    {
        return Admin::query()
            ->with([
                'roles',
                'roles.permissions',
            ])
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(admins.name)'), 'like', '%' . mb_strtolower($search) . '%')
                    ->orWhere(DB::raw('lower(admins.email)'), 'like', '%' . mb_strtolower($search) . '%');
            });
    }
}
