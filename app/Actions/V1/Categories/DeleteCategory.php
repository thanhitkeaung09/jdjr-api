<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\Exceptions\DeleteCategoryException;
use App\Models\Category;
use App\Services\FileStorage\FileStorage;
use JustSteveKing\StatusCode\Http;

final readonly class DeleteCategory
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Category $category): bool
    {
        $this->checkToDelete($category);

        $this->fileStorage->delete($category->icon);

        return (bool) $category->delete();
    }

    private function checkToDelete(Category $category): void
    {
        if ($category->subcategories()->count() > 0) {
            throw new DeleteCategoryException(
                message: \trans('message.delete_category.subcategories_exist'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }
    }
}
