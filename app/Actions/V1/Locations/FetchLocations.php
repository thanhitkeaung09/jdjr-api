<?php

declare(strict_types=1);

namespace App\Actions\V1\Locations;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchLocations
{
    public function handle(?string $search = null): Builder
    {
        return Location::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(locations.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
