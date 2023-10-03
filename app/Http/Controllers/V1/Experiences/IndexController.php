<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Experiences;

use App\Actions\V1\Experiences\FetchExperiences;
use App\Http\Resources\V1\ExperienceResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchExperiences $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: ExperienceResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
