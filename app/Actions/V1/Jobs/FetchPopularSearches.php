<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\PopularSearch;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchPopularSearches
{
    public function handle(): Builder
    {
        return PopularSearch::query()->orderBy('job_title');
    }
}
