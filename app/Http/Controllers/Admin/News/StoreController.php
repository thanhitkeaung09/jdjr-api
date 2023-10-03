<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\News;

use App\Actions\V1\News\CreateNews;
use App\Http\Requests\V1\News\UpsertRequest;
use App\Http\Resources\V1\NewsResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateNews $createNews,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new NewsResource(
                resource: $this->createNews->handle($request->payload()),
            ),
        );
    }
}
