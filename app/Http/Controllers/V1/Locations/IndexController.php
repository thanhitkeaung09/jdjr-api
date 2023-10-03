<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Locations;

use App\Actions\V1\Locations\FetchLocations;
use App\Http\Resources\V1\LocationResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchLocations $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: LocationResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
