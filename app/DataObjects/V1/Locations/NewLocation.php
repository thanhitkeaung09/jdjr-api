<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Locations;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewLocation implements DataObjectContract
{
    public function __construct(
        public string $name,
    ) {
    }

    /**
     * @param array{name:string} $attributes
     *
     * @return NewLocation
     */
    public static function of(array $attributes): NewLocation
    {
        return new NewLocation(
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
