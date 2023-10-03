<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Locations;

use App\Http\Resources\V1\LocationResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Location;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Location $location): Responsable
    {
        return new ModelResponse(
            data: new LocationResource(
                resource: $location,
            ),
        );
    }
}
