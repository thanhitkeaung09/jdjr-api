<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final readonly class FetchSubcategoriesByCategory
{
    public function handle(Category $category): Collection
    {
        return $category->subcategories()->orderBy('name')->get();
    }
}
