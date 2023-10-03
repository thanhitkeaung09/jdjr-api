<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Folders;

use App\Actions\V1\Auth\CheckOwner;
use App\Actions\V1\Folders\FetchFolder;
use App\Http\Resources\V1\FolderResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Folder;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __construct(
        private CheckOwner $checkOwner,
        private FetchFolder $query,
    ) {
    }

    public function __invoke(Folder $folder): Responsable
    {
        $this->checkOwner->handle($folder->user);

        return new ModelResponse(
            data: new FolderResource(
                resource: $this->query->handle($folder),
            ),
        );
    }
}
