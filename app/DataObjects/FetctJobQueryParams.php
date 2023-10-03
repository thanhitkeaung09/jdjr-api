<?php

declare(strict_types=1);

namespace App\DataObjects;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class FetctJobQueryParams implements DataObjectContract
{
    public function __construct()
    {
    }

    /**
     * @param array $attributes
     *
     * @return FetctJobQueryParams
     */
    public static function of(array $attributes): FetctJobQueryParams
    {
        return new FetctJobQueryParams();
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [];
    }
}
