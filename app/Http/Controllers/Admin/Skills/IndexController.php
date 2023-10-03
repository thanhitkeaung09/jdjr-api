<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Skills;

use App\Actions\V1\Skills\FetchSkills;
use App\Http\Resources\V1\SkillResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchSkills $query,
    ) {
    }
    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: SkillResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: SkillResource::collection(
                resource: $this->query->handle($request->query('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
