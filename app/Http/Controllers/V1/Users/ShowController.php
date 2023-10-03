<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Users;

use App\Actions\V1\Users\FetchUser;
use App\Http\Resources\V1\UserResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;

final class ShowController
{
    public function __construct(
        private FetchUser $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new ModelResponse(
            data: new UserResource(
                resource: $this->query->handle(Auth::user()),
            ),
        );
    }
}
