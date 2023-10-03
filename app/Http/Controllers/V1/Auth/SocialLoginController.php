<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\SocialLogin;
use App\Http\Requests\V1\Auth\SocialLoginRequest;
use App\Http\Responses\V1\TokenResponse;
use Illuminate\Contracts\Support\Responsable;

final class SocialLoginController
{
    public function __construct(
        private readonly SocialLogin $socialLogin,
    ) {
    }

    public function __invoke(SocialLoginRequest $request): Responsable
    {
        return new TokenResponse(
            data: $this->socialLogin->handle(
                data: $request->payload()
            ),
        );
    }
}
