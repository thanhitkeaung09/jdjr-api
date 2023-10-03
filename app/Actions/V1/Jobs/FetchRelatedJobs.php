<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Job;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchRelatedJobs
{
    public function handle(Job $job): Builder
    {
        $categoryId = $job->subcategory->category_id;

        $subcategories = Subcategory::query()->where('category_id', $categoryId)->get();

        return Job::query()
            ->with(['description'])
            ->whereIn('subcategory_id', $subcategories->pluck('id')->toArray())
            ->whereNot('id', $job->id)
            ->latest();
    }
}
