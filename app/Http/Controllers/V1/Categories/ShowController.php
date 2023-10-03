<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Categories;

use App\Actions\V1\Categories\FetchSubcategoriesByCategory;
use App\Http\Resources\V1\SubcategoryResource;
use App\Http\Responses\V1\CollectionResponse;
use App\Models\Category;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __construct(
        private FetchSubcategoriesByCategory $query,
    ) {
    }

    public function __invoke(Category $category): Responsable
    {
        return new CollectionResponse(
            data: SubcategoryResource::collection(
                resource: $this->query->handle($category),
            ),
            warp: 'data',
        );
    }
}
