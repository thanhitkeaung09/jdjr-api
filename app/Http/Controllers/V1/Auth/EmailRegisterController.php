<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\RegisterWithEmail;
use App\Http\Requests\V1\Auth\EmailRegisterRequest;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;

final class EmailRegisterController
{
    public function __construct(
        private readonly RegisterWithEmail $registerWithEmail
    ) {
    }

    public function __invoke(EmailRegisterRequest $request): Responsable
    {
        $this->registerWithEmail->handle(
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => \trans('message.register.success'),
            ]
        );
    }
}
