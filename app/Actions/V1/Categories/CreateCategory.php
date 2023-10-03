<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\DataObjects\V1\Categories\NewCategory;
use App\Models\Category;
use App\Services\FileStorage\FileStorage;

final readonly class CreateCategory
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(NewCategory $data): Category
    {
        $path = $this->fileStorage->upload(
            folder: \strval(\config('folders.categories')),
            file: $data->icon,
        );

        return Category::query()->create([
            ...$data->toArray(),
            'icon' => $path,
        ]);
    }
}
