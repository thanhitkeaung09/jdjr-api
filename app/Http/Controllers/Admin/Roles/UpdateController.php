<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Actions\V1\Roles\UpdateRole;
use App\Http\Requests\V1\Roles\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;
use Spatie\Permission\Models\Role;

final readonly class UpdateController
{
    public function __construct(
        private UpdateRole $updateRole,
    ) {
    }

    public function __invoke(Role $role, UpsertRequest $request): Responsable
    {
        $status = $this->updateRole->handle($role, $request->payload());

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ]
        );
    }
}
