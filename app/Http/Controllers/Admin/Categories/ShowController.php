<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Resources\V1\CategoryResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Category;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Category $category): Responsable
    {
        return new ModelResponse(
            data: new CategoryResource(
                resource: $category->load('subcategories'),
            ),
        );
    }
}
