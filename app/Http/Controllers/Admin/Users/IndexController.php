<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Actions\V1\Users\FetchUsers;
use App\Http\Resources\V1\UserResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchUsers $query
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: UserResource::collection(
                resource: $this->query->handle(request('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
