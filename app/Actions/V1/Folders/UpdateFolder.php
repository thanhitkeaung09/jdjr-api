<?php

declare(strict_types=1);

namespace App\Actions\V1\Folders;

use App\DataObjects\V1\Folders\NewFolderData;
use App\Models\Folder;

final readonly class UpdateFolder
{
    public function handle(Folder $folder, NewFolderData $data): bool
    {
        return $folder->update($data->toArray());
    }
}
