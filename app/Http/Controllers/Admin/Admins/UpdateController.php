<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Admins;

use App\Actions\V1\Admins\UpdateAdmin;
use App\Http\Requests\V1\Admins\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Admin;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateAdmin $updateAdmin,
    ) {
    }

    public function __invoke(Admin $admin, UpsertRequest $request): Responsable
    {
        $status = $this->updateAdmin->handle(
            admin: $admin,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
