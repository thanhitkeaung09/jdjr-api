<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Saves;

use App\Actions\V1\Saves\DispatchSaveUnsaveEvent;
use App\Actions\V1\Saves\SaveAction;
use App\Http\Requests\V1\Saves\StoreRequest;
use App\Http\Resources\V1\SavableResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private SaveAction $saveAction,
        private DispatchSaveUnsaveEvent $dispatchSaveUnsaveEvent,
    ) {
    }

    public function __invoke(StoreRequest $request): Responsable
    {
        return new ModelResponse(
            data: new SavableResource(
                resource: $this->saveAction->handle($request->payload()),
            ),
        );
    }
}
