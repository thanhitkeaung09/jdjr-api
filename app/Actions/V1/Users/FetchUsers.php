<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchUsers
{
    public function handle(?string $search = null): Builder
    {
        return User::query()
            ->with([
                'experience',
                'location',
                'interests',
                'skills',
                'folders',
            ])
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(users.name)'), 'like', '%' . mb_strtolower($search) . '%')
                    ->orWhere(DB::raw('lower(users.email)'), 'like', '%' . mb_strtolower($search) . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
    }
}
