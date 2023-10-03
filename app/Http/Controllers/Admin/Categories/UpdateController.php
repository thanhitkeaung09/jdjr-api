<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\UpdateCategory;
use App\Http\Requests\V1\Categories\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Category;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateCategory $updateCategory,
    ) {
    }
    public function __invoke(Category $category, UpsertRequest $request): Responsable
    {
        $status = $this->updateCategory->handle(
            category: $category,
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
