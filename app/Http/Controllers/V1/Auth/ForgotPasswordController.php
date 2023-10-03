<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\ForgotPassword;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;

final class ForgotPasswordController
{
    public function __construct(
        private readonly ForgotPassword $forgotPassword,
    ) {
    }

    public function __invoke(ForgotPasswordRequest $request): Responsable
    {
        $this->forgotPassword->handle($request->validated('email'));

        return new MessageResponse(
            data: [
                'message' => \trans('message.password.forgot'),
            ],
        );
    }
}
