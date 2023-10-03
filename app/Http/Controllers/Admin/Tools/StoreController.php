<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Tools;

use App\Actions\V1\Tools\CreateTool;
use App\Http\Requests\V1\Tools\UpsertRequest;
use App\Http\Resources\V1\ToolResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateTool $createTool,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new ToolResource(
                resource: $this->createTool->handle($request->payload()),
            ),
        );
    }
}
