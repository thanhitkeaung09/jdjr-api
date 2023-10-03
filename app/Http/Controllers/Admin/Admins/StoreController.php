<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Admins;

use App\Actions\V1\Admins\CreateAdmin;
use App\Http\Requests\V1\Admins\UpsertRequest;
use App\Http\Resources\V1\AdminResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final class StoreController
{
    public function __construct(
        private readonly CreateAdmin $createAdmin,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new AdminResource(
                resource: $this->createAdmin->handle(
                    data: $request->payload(),
                ),
            ),
        );
    }
}
