<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Levels;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewLevel implements DataObjectContract
{
    public function __construct(
        public string $name,
    ) {
    }

    /**
     * @param array{name:string} $attributes
     *
     * @return NewLevel
     */
    public static function of(array $attributes): NewLevel
    {
        return new NewLevel(
            name: $attributes['name'],
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
