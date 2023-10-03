<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchCategories
{
    public function handle(?string $search = null): Builder
    {
        return Category::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(categories.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
