<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\DataObjects\V1\Categories\CategoryIds;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchJobTitles
{
    public function handle(CategoryIds $data): Builder
    {
        return Job::query()
            ->whereHas('subcategory', function ($q) use ($data): void {
                $q->whereIn('category_id', $data->toArray());
            })
            ->orderBy('title');
    }
}
