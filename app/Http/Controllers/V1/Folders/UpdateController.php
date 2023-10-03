<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Folders;

use App\Actions\V1\Auth\CheckOwner;
use App\Actions\V1\Folders\UpdateFolder;
use App\Http\Requests\V1\Folders\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Folder;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private CheckOwner $checkOwner,
        private UpdateFolder $updateFolder,
    ) {
    }

    public function __invoke(Folder $folder, UpsertRequest $request): Responsable
    {
        $this->checkOwner->handle($folder->user);

        $status = $this->updateFolder->handle(
            folder: $folder,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
