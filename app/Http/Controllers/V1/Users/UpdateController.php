<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Users;

use App\Actions\V1\Users\UpdateUser;
use App\Http\Requests\V1\Users\UpdateRequest;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;

final class UpdateController
{
    public function __construct(
        private readonly UpdateUser $updateUser,
    ) {
    }

    public function __invoke(UpdateRequest $request): Responsable
    {
        $status = $this->updateUser->handle(
            user: Auth::user(),
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    trans('message.update.success') :
                    trans('message.update.fail'),
            ]
        );
    }
}
