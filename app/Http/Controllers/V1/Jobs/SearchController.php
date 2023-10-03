<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Jobs;

use App\Actions\V1\Jobs\SearchJobs;
use App\DataObjects\V1\Jobs\JobSearchFilters;
use App\Http\Resources\V1\JobResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final readonly class SearchController
{
    public function __construct(
        private SearchJobs $query,
    ) {
    }

    public function __invoke(Request $request): Responsable
    {
        $filters = JobSearchFilters::of($request->query());

        return new CollectionResponse(
            data: JobResource::collection(
                resource: $this->query->handle($filters)->get(),
            ),
            warp: 'data',
        );
    }
}
