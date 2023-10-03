<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AppVersions;

use App\Actions\V1\AppVersions\FetchAppVersions;
use App\Http\Resources\V1\AppVersionResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchAppVersions $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: AppVersionResource::collection(
                resource: $this->query->handle()->paginate(\config('database.pagination')),
            ),
        );
    }
}
