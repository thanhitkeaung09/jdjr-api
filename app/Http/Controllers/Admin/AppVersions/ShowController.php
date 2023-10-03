<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AppVersions;

use App\Http\Resources\V1\AppVersionResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\AppVersion;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(AppVersion $appVersion): Responsable
    {
        return new ModelResponse(
            data: new AppVersionResource(
                resource: $appVersion,
            ),
        );
    }
}
