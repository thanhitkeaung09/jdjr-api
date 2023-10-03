<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Categories;

use App\Actions\V1\Categories\FetchJobTitles;
use App\Http\Requests\V1\Categories\JobTitlesRequest;
use App\Http\Resources\V1\JobTitlesResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final class JobTitlesController
{
    public function __construct(
        private readonly FetchJobTitles $fetchJobTitles,
    ) {
    }

    public function __invoke(JobTitlesRequest $request): Responsable
    {
        return new CollectionResponse(
            data: JobTitlesResource::collection(
                resource: $this->fetchJobTitles->handle(
                    data: $request->payload(),
                )->get(),
            ),
            warp: 'data',
        );
    }
}
