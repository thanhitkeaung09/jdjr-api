<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Users;

use App\Actions\V1\Users\GetRecentlyReadNews;
use App\Http\Resources\V1\NewsResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class RecentlyReadController
{
    public function __construct(
        private GetRecentlyReadNews $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: NewsResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
