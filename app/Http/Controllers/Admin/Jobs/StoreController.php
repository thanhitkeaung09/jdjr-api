<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\CreateJob;
use App\Http\Requests\V1\Jobs\StoreRequest;
use App\Http\Resources\V1\JobResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateJob $createJob,
    ) {
    }

    public function __invoke(StoreRequest $request): Responsable
    {
        return new ModelResponse(
            data: new JobResource(
                resource: $this->createJob->handle($request->payload()),
            ),
        );
    }
}
