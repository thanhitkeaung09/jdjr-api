<?php

declare(strict_types=1);

namespace App\Actions\V1\Locations;

use App\DataObjects\V1\Locations\NewLocation;
use App\Models\Location;

final readonly class UpdateLocation
{
    public function handle(Location $location, NewLocation $data): bool
    {
        return $location->update($data->toArray());
    }
}
