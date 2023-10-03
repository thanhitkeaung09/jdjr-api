<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Experiences;

use App\Actions\V1\Experiences\FetchLevels;
use App\Http\Resources\V1\LevelResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class AllLevelsController
{
    public function __construct(
        private FetchLevels $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: LevelResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data'
        );
    }
}
