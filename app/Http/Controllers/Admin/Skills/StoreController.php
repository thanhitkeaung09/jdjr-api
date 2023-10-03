<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Skills;

use App\Actions\V1\Skills\CreateSkill;
use App\Http\Requests\V1\Skills\UpsertRequest;
use App\Http\Resources\V1\SkillResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateSkill $createSkill,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new SkillResource(
                resource: $this->createSkill->handle($request->payload()),
            ),
        );
    }
}
