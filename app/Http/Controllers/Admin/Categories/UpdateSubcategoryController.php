<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\UpdateSubcategory;
use App\Http\Requests\V1\Categories\UpsertSubcategoryRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Subcategory;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateSubcategoryController
{
    public function __construct(
        private UpdateSubcategory $updateSubcategory,
    ) {
    }

    public function __invoke(
        string $category,
        Subcategory $subcategory,
        UpsertSubcategoryRequest $request,
    ): Responsable {
        $status = $this->updateSubcategory->handle(
            subcategory: $subcategory,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
