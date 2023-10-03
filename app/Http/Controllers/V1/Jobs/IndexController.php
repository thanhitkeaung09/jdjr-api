<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Jobs;

use App\Actions\V1\Jobs\FetchJobsByUserAttributes;
use App\Http\Resources\V1\JobResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Auth;

final readonly class IndexController
{
    public function __construct(
        private FetchJobsByUserAttributes $query,
    ) {
    }
    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: JobResource::collection(
                resource: $this->query->handle(
                    user: Auth::user(),
                )->paginate(\config('database.pagination')),
            ),
        );
    }
}
