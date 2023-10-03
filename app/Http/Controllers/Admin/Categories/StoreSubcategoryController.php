<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\CreateSubcategory;
use App\Http\Requests\V1\Categories\UpsertSubcategoryRequest;
use App\Http\Resources\V1\SubcategoryResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreSubcategoryController
{
    public function __construct(
        private CreateSubcategory $createSubcategory,
    ) {
    }

    public function __invoke(UpsertSubcategoryRequest $request): Responsable
    {
        return new ModelResponse(
            data: new SubcategoryResource(
                resource: $this->createSubcategory->handle($request->payload()),
            ),
        );
    }
}
