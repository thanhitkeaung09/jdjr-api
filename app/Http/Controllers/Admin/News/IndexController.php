<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\News;

use App\Actions\V1\News\FetchAllNews;
use App\Http\Resources\V1\NewsResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchAllNews $query,
    ) {
    }
    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: NewsResource::collection(
                resource: $this->query->handle(request('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
