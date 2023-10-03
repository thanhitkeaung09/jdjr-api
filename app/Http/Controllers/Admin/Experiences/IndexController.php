<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Experiences;

use App\Actions\V1\Experiences\FetchExperiences;
use App\Http\Resources\V1\ExperienceResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchExperiences $query,
    ) {
    }

    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: ExperienceResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: ExperienceResource::collection(
                resource: $this->query->handle($request->query('search'))->with('level')->paginate(\config('database.pagination')),
            ),
        );
    }
}
