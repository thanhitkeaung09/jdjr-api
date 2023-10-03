<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Users;

use App\Actions\V1\Users\DeleteUser;
use App\Http\Responses\V1\MessageResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteUser $deleteUser,
    ) {
    }

    public function __invoke(User $user): Responsable
    {
        $status = $this->deleteUser->handle($user);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
