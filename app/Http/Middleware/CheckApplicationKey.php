<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Responses\ApiErrorResponse;
use App\Models\ApplicationKey;
use Closure;
use Illuminate\Http\Request;
use JustSteveKing\StatusCode\Http;
use Symfony\Component\HttpFoundation\Response;

final class CheckApplicationKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $appId = $request->header('app-id');
        $appSecrete = $request->header('app-secrete');

        if ( ! ($appId && $appSecrete)) {
            return $this->unauthorizedResponse($request);
        }

        $appKey = ApplicationKey::query()
            ->where('app_id', $appId)
            ->where('app_secrete', $appSecrete)
            ->first();

        if ( ! isset($appKey)) {
            return $this->unauthorizedResponse($request);
        }

        if ($appKey->obsoleted) {
            return $this->oudatedResponse($request);
        }

        return $next($request);
    }

    private function unauthorizedResponse(Request $request): Response
    {
        return (new ApiErrorResponse(
            title: \strval(\trans('message.exceptions.title.unauthorized')),
            description: \strval(\trans('message.exceptions.permission_denied')),
            status: Http::FORBIDDEN,
        ))->toResponse($request);
    }

    private function oudatedResponse(Request $request): Response
    {
        return (new ApiErrorResponse(
            title: \strval(\trans('message.exceptions.title.outdated')),
            description: \strval(\trans('message.exceptions.invalid_app_keys')),
            status: Http::UPGRADE_REQUIRED,
        ))->toResponse($request);
    }
}
