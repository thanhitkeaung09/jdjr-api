<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\FetchJobs;
use App\Http\Resources\V1\JobResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchJobs $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: JobResource::collection(
                resource: $this->query->handle(request('search'))->with('popular')->paginate(\config('database.pagination')),
            ),
        );
    }
}
