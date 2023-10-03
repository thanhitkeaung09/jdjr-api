<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Subcategories;

use App\Actions\V1\Subcategories\FetchSubcategories;
use App\Http\Resources\V1\SubcategoryResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchSubcategories $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: SubcategoryResource::collection(
                resource: $this->query->handle()->get(),
            ),
        );
    }
}
