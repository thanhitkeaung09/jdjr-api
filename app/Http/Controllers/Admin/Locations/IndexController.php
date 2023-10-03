<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Locations;

use App\Actions\V1\Locations\FetchLocations;
use App\Http\Resources\V1\LocationResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchLocations $query,
    ) {
    }

    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: LocationResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: LocationResource::collection(
                resource: $this->query->handle(request('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
