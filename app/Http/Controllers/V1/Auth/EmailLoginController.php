<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\EmailLogin;
use App\Exceptions\LoginException;
use App\Http\Requests\V1\Auth\EmailLoginRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\V1\TokenResponse;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final class EmailLoginController
{
    public function __construct(
        private readonly EmailLogin $emailLogin,
    ) {
    }

    public function __invoke(EmailLoginRequest $request): Responsable
    {
        try {
            return new TokenResponse(
                data: $this->emailLogin->handle(
                    data: $request->payload(),
                ),
            );
        } catch (LoginException $e) {
            return new ApiErrorResponse(
                title: trans('message.login.fail'),
                description: $e->getMessage(),
                status: Http::UNAUTHORIZED,
            );
        }
    }
}
