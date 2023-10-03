<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Locations;

use App\Actions\V1\Locations\CreateLocation;
use App\Http\Requests\V1\Locations\UpsertRequest;
use App\Http\Resources\V1\LocationResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateLocation $createLocation,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new LocationResource(
                resource: $this->createLocation->handle($request->payload()),
            ),
        );
    }
}
