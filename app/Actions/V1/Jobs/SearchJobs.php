<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\DataObjects\V1\Jobs\JobSearchFilters;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class SearchJobs
{
    public function handle(JobSearchFilters $filters): Builder
    {
        return Job::query()
            ->when($filters->search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(jobs.title)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->when($filters->categoryId, static function (Builder $query, string $categoryId): void {
                $query->whereHas('subcategory', fn ($q) => $q->where('category_id', $categoryId));
            })
            ->when($filters->subcategoryId, static function (Builder $query, string $subcategoryId): void {
                $query->where('subcategory_id', $subcategoryId);
            })
            ->when($filters->level, static function (Builder $query, string $level): void {
                $query->whereHas('experiences', fn ($q) => $q->where('level_id', $level));
            })
            ->latest();
    }
}
