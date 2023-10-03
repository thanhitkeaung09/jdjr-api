<?php

declare(strict_types=1);

namespace App\Actions\V1\Tools;

use App\Models\Tool;
use App\Services\FileStorage\FileStorage;

final readonly class DeleteTool
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Tool $tool): bool
    {
        $this->fileStorage->delete($tool->icon);

        return $tool->delete();
    }
}
