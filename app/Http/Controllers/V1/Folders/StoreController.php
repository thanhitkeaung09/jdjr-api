<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Folders;

use App\Actions\V1\Folders\CreateFolder;
use App\Http\Requests\V1\Folders\UpsertRequest;
use App\Http\Resources\V1\FolderResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateFolder $createFolder,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new FolderResource(
                resource: $this->createFolder->handle($request->payload()),
            ),
        );
    }
}
