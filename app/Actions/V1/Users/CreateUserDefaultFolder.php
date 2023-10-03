<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Actions\V1\Folders\CreateFolder;
use App\DataObjects\V1\Folders\NewFolderData;
use App\Models\Folder;
use App\Models\User;

final readonly class CreateUserDefaultFolder
{
    public function __construct(
        private CreateFolder $createFolder
    ) {
    }

    public function handle(User $user): Folder
    {
        return $this->createFolder->handle(NewFolderData::of([
            'name' => 'Read Later',
            'user' => $user,
        ]));
    }
}
