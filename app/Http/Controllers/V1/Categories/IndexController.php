<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Categories;

use App\Actions\V1\Categories\FetchCategories;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final class IndexController
{
    public function __construct(
        private readonly FetchCategories $fetchCategories,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: CategoryResource::collection(
                resource: $this->fetchCategories->handle()->get(),
            ),
            warp: 'data'
        );
    }
}
