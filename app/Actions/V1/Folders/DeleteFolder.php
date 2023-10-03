<?php

declare(strict_types=1);

namespace App\Actions\V1\Folders;

use App\Models\Folder;

final readonly class DeleteFolder
{
    public function handle(Folder $folder): bool
    {
        // delete jobs and news in this folder.
        $folder->jobs()->detach();
        $folder->news()->detach();

        return $folder->delete();
    }
}
