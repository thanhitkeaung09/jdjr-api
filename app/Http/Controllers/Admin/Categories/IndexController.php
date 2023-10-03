<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\FetchCategories;
use App\Http\Resources\V1\CategoryResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchCategories $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: CategoryResource::collection(
                resource: $this->query->handle(request('search'))->with('subcategories')->paginate(\config('database.pagination')),
            ),
        );
    }
}
