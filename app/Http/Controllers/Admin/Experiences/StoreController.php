<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Experiences;

use App\Actions\V1\Experiences\CreateExperience;
use App\Http\Requests\V1\Experiences\UpsertRequest;
use App\Http\Resources\V1\ExperienceResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateExperience $createExperience,
    ) {
    }
    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new ExperienceResource(
                resource: $this->createExperience->handle($request->payload()),
            ),
        );
    }
}
