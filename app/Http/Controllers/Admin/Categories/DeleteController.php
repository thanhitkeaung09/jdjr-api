<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\DeleteCategory;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Category;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteCategory $deleteCategory,
    ) {
    }

    public function __invoke(Category $category): Responsable
    {
        $status = $this->deleteCategory->handle($category);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
