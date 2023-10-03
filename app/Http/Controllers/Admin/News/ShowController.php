<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\News;

use App\Http\Resources\V1\NewsResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\News;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(News $news): Responsable
    {
        return new ModelResponse(
            data: new NewsResource(
                resource: $news,
            ),
        );
    }
}
