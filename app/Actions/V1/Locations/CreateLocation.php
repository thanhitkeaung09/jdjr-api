<?php

declare(strict_types=1);

namespace App\Actions\V1\Locations;

use App\DataObjects\V1\Locations\NewLocation;
use App\Models\Location;

final readonly class CreateLocation
{
    public function handle(NewLocation $data): Location
    {
        return Location::query()->create($data->toArray());
    }
}
