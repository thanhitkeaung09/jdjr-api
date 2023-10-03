<?php

declare(strict_types=1);

namespace App\Actions\V1\Tools;

use App\DataObjects\V1\Tools\NewTool;
use App\Models\Tool;
use App\Services\FileStorage\FileStorage;

final readonly class CreateTool
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(NewTool $data): Tool
    {
        $path = $this->fileStorage->upload(
            folder: \config('folders.tools'),
            file: $data->icon,
        );

        return Tool::query()->create(
            attributes: [
                ...$data->toArray(),
                'icon' => $path,
            ],
        );
    }
}
