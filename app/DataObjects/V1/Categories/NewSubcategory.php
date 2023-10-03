<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Categories;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewSubcategory implements DataObjectContract
{
    public function __construct(
        public string $name,
        public string $categoryId,
    ) {
    }

    /**
     * @param array{name:string,category_id:string} $attributes
     *
     * @return NewSubcategory
     */
    public static function of(array $attributes): NewSubcategory
    {
        return new NewSubcategory(
            name: $attributes['name'],
            categoryId: $attributes['category_id'],
        );
    }

    /**
     * @return array{name:string,category_id:string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->categoryId,
        ];
    }
}
