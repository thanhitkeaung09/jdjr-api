<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\FetchJob;
use App\Http\Resources\V1\JobResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Job;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __construct(
        private FetchJob $query,
    ) {
    }

    public function __invoke(Job $job): Responsable
    {
        return new ModelResponse(
            data: new JobResource(
                resource: $this->query->handle(
                    job: $job,
                    questionFilter: false,
                )->load(['location', 'subcategory.category', 'experiences.level']),
            ),
        );
    }
}
