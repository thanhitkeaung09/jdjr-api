<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Jobs;

use App\Actions\V1\Jobs\FetchRelatedJobs;
use App\Http\Resources\V1\JobResource;
use App\Http\Responses\V1\CollectionResponse;
use App\Models\Job;
use Illuminate\Contracts\Support\Responsable;

final readonly class RelatedJobsController
{
    public function __construct(
        private FetchRelatedJobs $query,
    ) {
    }

    public function __invoke(Job $job): Responsable
    {
        return new CollectionResponse(
            data: JobResource::collection(
                resource: $this->query->handle($job)->get(),
            ),
            warp: 'data',
        );
    }
}
