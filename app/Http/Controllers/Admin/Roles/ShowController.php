<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Actions\V1\Roles\FetchRole;
use App\Http\Resources\V1\RoleResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;
use Spatie\Permission\Models\Role;

final readonly class ShowController
{
    public function __construct(
        private FetchRole $query,
    ) {
    }

    public function __invoke(Role $role): Responsable
    {
        return new ModelResponse(
            data: new RoleResource(
                resource: $this->query->handle($role),
            ),
        );
    }
}
