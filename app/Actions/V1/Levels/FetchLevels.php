<?php

declare(strict_types=1);

namespace App\Actions\V1\Levels;

use App\Models\Level;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchLevels
{
    public function handle(?string $search = null): Builder
    {
        return Level::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(levels.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
