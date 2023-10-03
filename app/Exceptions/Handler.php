<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Request;
use JustSteveKing\StatusCode\Http;
use App\Http\Responses\ApiErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return new ApiErrorResponse(
                    title: \strval(\trans('message.exceptions.title.not_found')),
                    description: $e->getMessage(),
                    status: Http::NOT_FOUND,
                );
            }
        });

        $this->renderable(function (AuthenticationException $e) {
            return new ApiErrorResponse(
                title: \strval(\trans('message.exceptions.title.unauthenicated')),
                description: $e->getMessage(),
                status: Http::UNAUTHORIZED,
            );
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return new ApiErrorResponse(
                title: \strval(\trans('message.exceptions.title.unauthorized')),
                description: $e->getMessage(),
                status: Http::FORBIDDEN,
            );
        });
    }
}
