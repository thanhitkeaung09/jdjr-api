<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Jobs;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class JobSearchFilters implements DataObjectContract
{
    public function __construct(
        public string|null $search = '',
        public string|null $categoryId = null,
        public string|null $subcategoryId = null,
        public string|null $level = null,
    ) {
    }

    /**
     * @param array{search?:string,category_id?:string,subcategory_id?:string,level?:string} $attributes
     *
     * @return JobSearchFilters
     */
    public static function of(array $attributes): JobSearchFilters
    {
        return new JobSearchFilters(
            search: $attributes['search'] ?? '',
            categoryId: $attributes['category_id'] ?? null,
            subcategoryId: $attributes['subcategory_id'] ?? null,
            level: $attributes['level'] ?? null,
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [];
    }
}
