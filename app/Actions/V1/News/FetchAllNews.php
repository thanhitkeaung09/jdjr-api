<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchAllNews
{
    public function handle(?string $search = null): Builder
    {
        return News::query()
            ->withCount('likes')
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(news.title)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->latest();
    }
}
