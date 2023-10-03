<?php

declare(strict_types=1);

namespace App\Actions\V1\Folders;

use App\DataObjects\V1\Folders\NewFolderData;
use App\Models\Folder;

final readonly class CreateFolder
{
    public function handle(NewFolderData $data): Folder
    {
        return Folder::query()->create($data->toArray());
    }
}
