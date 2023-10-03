<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\AppVersions;

use App\Actions\V1\AppVersions\FetchAppVersions;
use App\Http\Resources\V1\AppVersionResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final readonly class ShowController
{
    public function __construct(
        private FetchAppVersions $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        $resource = $this->query->handle()->first();

        if (null === $resource) {
            return new ApiErrorResponse(
                title: \strval(\trans('message.exceptions.title.not_found')),
                description: \trans('message.app-version.not_found'),
                status: Http::NOT_FOUND,
            );
        }

        return new ModelResponse(
            data: new AppVersionResource(
                resource: $resource,
            ),
        );
    }
}
