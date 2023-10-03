<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Categories;

use Illuminate\Http\UploadedFile;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewCategory implements DataObjectContract
{
    public function __construct(
        public string $name,
        public UploadedFile|null $icon,
    ) {
    }

    /**
     * @param array{name:string,icon?:UploadedFile} $attributes
     *
     * @return NewCategory
     */
    public static function of(array $attributes): NewCategory
    {
        return new NewCategory(
            name: $attributes['name'],
            icon: $attributes['icon'],
        );
    }

    /**
     * @return array{name:string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
