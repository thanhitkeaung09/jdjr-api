<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\DataObjects\V1\Categories\NewSubcategory;
use App\Models\Subcategory;

final readonly class CreateSubcategory
{
    public function handle(NewSubcategory $data): Subcategory
    {
        return Subcategory::query()->create($data->toArray());
    }
}
