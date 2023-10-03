<?php

declare(strict_types=1);

namespace App\Actions\V1\Subcategories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchSubcategories
{
    public function handle(): Builder
    {
        return Subcategory::query()->orderBy('name');
    }
}
