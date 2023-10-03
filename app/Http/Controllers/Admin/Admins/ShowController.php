<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Admins;

use App\Actions\V1\Admins\FetchAdmin;
use App\Http\Resources\V1\AdminResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Admin;
use Illuminate\Contracts\Support\Responsable;

final class ShowController
{
    public function __construct(
        private readonly FetchAdmin $fetchAdmin,
    ) {
    }

    public function __invoke(Admin $admin): Responsable
    {
        return new ModelResponse(
            data: new AdminResource(
                resource: $this->fetchAdmin->handle($admin),
            ),
        );
    }
}
