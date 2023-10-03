<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Actions\V1\Roles\CreateRole;
use App\Http\Requests\V1\Roles\UpsertRequest;
use App\Http\Resources\V1\RoleResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateRole $createRole,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new RoleResource(
                resource: $this->createRole->handle($request->payload()),
            ),
        );
    }
}
