<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Actions\V1\Auth\AdminLoginAction;
use App\Exceptions\LoginException;
use App\Http\Requests\V1\Auth\AdminLoginRequest;
use App\Http\Resources\V1\AdminAuthResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final class LoginController
{
    public function __construct(
        private readonly AdminLoginAction $adminLoginAction,
    ) {
    }
    public function __invoke(AdminLoginRequest $request): Responsable
    {
        try {
            return new ModelResponse(
                data: new AdminAuthResource(
                    resource: $this->adminLoginAction->handle(
                        data: $request->payload(),
                    ),
                ),
            );
        } catch (LoginException $e) {
            return new ApiErrorResponse(
                title: \strval(\trans('message.exceptions.title.unauthenicated')),
                description: $e->getMessage(),
                status: Http::UNAUTHORIZED,
            );
        }
    }
}
