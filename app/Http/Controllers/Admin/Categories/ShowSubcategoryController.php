<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Resources\V1\SubcategoryResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowSubcategoryController
{
    public function __invoke(Category $category, Subcategory $subcategory): Responsable
    {
        return new ModelResponse(
            data: new SubcategoryResource(
                resource: $subcategory,
            ),
        );
    }
}
