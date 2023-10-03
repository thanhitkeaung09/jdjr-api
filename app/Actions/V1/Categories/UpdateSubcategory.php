<?php

declare(strict_types=1);

namespace App\Actions\V1\Categories;

use App\DataObjects\V1\Categories\NewSubcategory;
use App\Models\Subcategory;

final readonly class UpdateSubcategory
{
    public function handle(Subcategory $subcategory, NewSubcategory $data): bool
    {
        return $subcategory->update($data->toArray());
    }
}
