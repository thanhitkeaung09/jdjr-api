<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Levels;

use App\Actions\V1\Levels\CreateLevel;
use App\Http\Requests\V1\Levels\UpsertRequest;
use App\Http\Resources\V1\LevelResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateLevel $createLevel,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new LevelResource(
                resource: $this->createLevel->handle($request->payload()),
            ),
        );
    }
}
