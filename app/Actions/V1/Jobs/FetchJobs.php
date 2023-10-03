<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchJobs
{
    public function handle(?string $search = null): Builder
    {
        return Job::query()
            ->with([
                'subcategory.category',
            ])
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(jobs.title)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->latest();
    }
}
