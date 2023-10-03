<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Categories;

use App\Actions\V1\Categories\DeleteSubcategory;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Subcategory;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteSubcategoryController
{
    public function __construct(
        private DeleteSubcategory $deleteSubcategory,
    ) {
    }

    public function __invoke(string $category, Subcategory $subcategory): Responsable
    {
        $status = $this->deleteSubcategory->handle($subcategory);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
