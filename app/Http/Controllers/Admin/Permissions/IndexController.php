<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Permissions;

use App\Actions\V1\Permissions\FetchPermissions;
use App\Http\Resources\V1\PermissionResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchPermissions $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: PermissionResource::collection(
                resource: $this->query->handle()->get(),
            ),
        );
    }
}
