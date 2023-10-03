<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Roles;

use Illuminate\Support\Collection;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewRoleData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public Collection $permissions,
    ) {
    }

    /**
     * @param array{name:string,permissions:array} $attributes
     *
     * @return NewRoleData
     */
    public static function of(array $attributes): NewRoleData
    {
        return new NewRoleData(
            name: $attributes['name'],
            permissions: Collection::make($attributes['permissions']),
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
