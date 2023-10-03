<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Skills;

use App\Actions\V1\Skills\FetchSkillsByCategories;
use App\Http\Requests\V1\Skills\GetSkillsRequest;
use App\Http\Resources\V1\SkillResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchSkillsByCategories $query,
    ) {
    }

    public function __invoke(GetSkillsRequest $request): Responsable
    {
        return new CollectionResponse(
            data: SkillResource::collection(
                resource: $this->query->handle($request->payload())->get(),
            ),
            warp: 'data',
        );
    }
}
