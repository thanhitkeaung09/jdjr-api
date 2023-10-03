<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Roles;

use App\Actions\V1\Roles\DeleteRole;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;
use Spatie\Permission\Models\Role;

final readonly class DeleteController
{
    public function __construct(
        private DeleteRole $deleteRole,
    ) {
    }
    public function __invoke(Role $role): Responsable
    {
        $status = $this->deleteRole->handle($role);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
