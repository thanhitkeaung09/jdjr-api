<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\LogoutAction;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final class LogoutController
{
    public function __construct(
        private readonly LogoutAction $logoutAction,
    ) {
    }

    public function __invoke(Request $request): Responsable
    {
        $this->logoutAction->handle(
            user: $request->user(),
        );

        return new MessageResponse(
            data: [
                'message' => \trans('message.logout.success'),
            ],
        );
    }
}
