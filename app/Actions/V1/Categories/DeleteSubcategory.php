<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\Exceptions\DeleteSubcategoryException;
use App\Models\Subcategory;
use JustSteveKing\StatusCode\Http;

final readonly class DeleteSubcategory
{
    public function handle(Subcategory $subcategory): bool
    {
        $this->checkToDelete($subcategory);

        return $subcategory->delete();
    }

    private function checkToDelete(Subcategory $subcategory): void
    {
        if ($subcategory->jobs()->count() > 0) {
            throw new DeleteSubcategoryException(
                message: \trans('message.delete_subcategory.jobs_exist'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }
    }
}
