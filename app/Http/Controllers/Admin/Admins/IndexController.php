<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Admins;

use App\Actions\V1\Admins\FetchAdmins;
use App\Http\Resources\V1\AdminResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final class IndexController
{
    public function __construct(
        private readonly FetchAdmins $fetchAdmins,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: AdminResource::collection(
                resource: $this->fetchAdmins->handle(request('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
