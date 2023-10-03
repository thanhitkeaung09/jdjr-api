<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Jobs;

use App\Actions\V1\Jobs\FetchPopularSearches;
use App\Http\Resources\V1\PopularResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class PopularSearchesController
{
    public function __construct(
        private FetchPopularSearches $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: PopularResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data'
        );
    }
}
