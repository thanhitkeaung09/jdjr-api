<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Admins;

use App\Actions\V1\Admins\DeleteAdmin;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Admin;
use Illuminate\Contracts\Support\Responsable;

final class DeleteController
{
    public function __construct(
        private DeleteAdmin $deleteAdmin,
    ) {
    }

    public function __invoke(Admin $admin): Responsable
    {
        $status = $this->deleteAdmin->handle($admin);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
