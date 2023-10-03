<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Locations;

use App\Actions\V1\Locations\UpdateLocation;
use App\Http\Requests\V1\Locations\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Location;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateLocation $updateLocation,
    ) {
    }

    public function __invoke(Location $location, UpsertRequest $request): Responsable
    {
        $status = $this->updateLocation->handle(
            location: $location,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
