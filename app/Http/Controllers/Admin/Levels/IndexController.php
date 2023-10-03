<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Levels;

use App\Actions\V1\Levels\FetchLevels;
use App\Http\Resources\V1\LevelResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchLevels $query,
    ) {
    }
    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: LevelResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: LevelResource::collection(
                resource: $this->query->handle($request->query('search'))->paginate(),
            ),
        );
    }
}
