<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Actions\V1\Users\UpdateUser;
use App\Http\Requests\V1\Users\UpdateRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateUser $updateUser,
    ) {
    }

    public function __invoke(User $user, UpdateRequest $request): Responsable
    {
        $status = $this->updateUser->handle(
            user: $user,
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
