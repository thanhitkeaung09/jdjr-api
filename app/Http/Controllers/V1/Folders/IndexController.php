<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Folders;

use App\Actions\V1\Folders\FetchUserFolders;
use App\Http\Resources\V1\FolderResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchUserFolders $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: FolderResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
