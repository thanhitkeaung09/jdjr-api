<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\DataObjects\V1\Categories\NewCategory;
use App\Models\Category;
use App\Services\FileStorage\FileStorage;

final readonly class UpdateCategory
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Category $category, NewCategory $data): bool
    {
        $attributes = $data->toArray();

        if ($data->icon) {
            $attributes['icon'] = $this->fileStorage->upload(
                folder: \strval(\config('folders.categories')),
                file: $data->icon,
            );
        }

        return $category->update($attributes);
    }
}
