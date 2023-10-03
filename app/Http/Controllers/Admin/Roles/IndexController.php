<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Actions\V1\Roles\FetchRoles;
use App\Http\Resources\V1\RoleResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchRoles $query,
    ) {
    }

    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: RoleResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: RoleResource::collection(
                resource: $this->query->handle($request->query('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
