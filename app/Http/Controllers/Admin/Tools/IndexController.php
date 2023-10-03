<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Tools;

use App\Actions\V1\Tools\FetchTools;
use App\Http\Resources\V1\ToolResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchTools $query,
    ) {
    }
    public function __invoke(Request $request): Responsable
    {
        $type = $request->query('type');

        if ('all' === $type) {
            return new CollectionResponse(
                data: ToolResource::collection(
                    resource: $this->query->handle()->get(),
                ),
            );
        }

        return new PaginatedResourceResponse(
            resource: ToolResource::collection(
                resource: $this->query->handle($request->query('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
