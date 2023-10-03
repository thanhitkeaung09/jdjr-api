<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\CreateCategory;
use App\Http\Requests\V1\Categories\UpsertRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateCategory $createCategory,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new CategoryResource(
                resource: $this->createCategory->handle($request->payload()),
            ),
        );
    }
}
