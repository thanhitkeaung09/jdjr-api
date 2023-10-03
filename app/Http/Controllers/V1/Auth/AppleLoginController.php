<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Exceptions\LoginException;
use JustSteveKing\StatusCode\Http;
use App\Actions\V1\Auth\AppleLogin;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\V1\TokenResponse;
use App\Http\Requests\V1\Auth\AppleLoginRequest;

final readonly class AppleLoginController
{
    public function __construct(
        private AppleLogin $appleLogin,
    ) {
    }

    public function __invoke(AppleLoginRequest $request)
    {
        try {
            return new TokenResponse(
                data: $this->appleLogin->handle(
                    data: $request->payload()
                ),
            );
        } catch (LoginException $e) {
            return new ApiErrorResponse(
                title: 'Apple Login Failed!',
                description: $e->getMessage(),
                status: Http::NOT_ACCEPTABLE,
            );
        }
    }
}
