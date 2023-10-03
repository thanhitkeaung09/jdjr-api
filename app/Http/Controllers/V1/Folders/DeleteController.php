<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Folders;

use App\Actions\V1\Auth\CheckOwner;
use App\Actions\V1\Folders\DeleteFolder;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Folder;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private CheckOwner $checkOwner,
        private DeleteFolder $deleteFolder,
    ) {
    }

    public function __invoke(Folder $folder): Responsable
    {
        $this->checkOwner->handle($folder->user);

        return new MessageResponse(
            data: [
                'message' => $this->deleteFolder->handle($folder) ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
