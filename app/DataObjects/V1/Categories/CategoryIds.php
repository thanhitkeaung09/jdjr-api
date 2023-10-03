<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Categories;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class CategoryIds implements DataObjectContract
{
    public function __construct(
        public array $ids,
    ) {
    }

    /**
     * @param array{categories:array} $attributes
     *
     * @return CategoryIds
     */
    public static function of(array $attributes): CategoryIds
    {
        return new CategoryIds(
            ids: $attributes['categories'],
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->ids;
    }
}
