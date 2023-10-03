<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Locations;

use App\Actions\V1\Locations\DeleteLocation;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Location;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteLocation $deleteLocation,
    ) {
    }

    public function __invoke(Location $location): Responsable
    {
        $status = $this->deleteLocation->handle($location);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
