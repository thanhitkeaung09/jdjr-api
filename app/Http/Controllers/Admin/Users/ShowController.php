<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Actions\V1\Users\FetchUser;
use App\Http\Resources\V1\UserResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __construct(
        private FetchUser $query,
    ) {
    }

    public function __invoke(User $user): Responsable
    {
        return new ModelResponse(
            data: new UserResource(
                resource: $this->query->handle($user),
            ),
        );
    }
}
