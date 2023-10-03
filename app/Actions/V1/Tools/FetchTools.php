<?php

declare(strict_types=1);

namespace App\Actions\V1\Tools;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchTools
{
    public function handle(?string $search = null): Builder
    {
        return Tool::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(tools.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
