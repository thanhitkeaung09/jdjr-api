<?php

declare(strict_types=1);

namespace App\Actions\V1\Tools;

use App\DataObjects\V1\Tools\NewTool;
use App\Models\Tool;
use App\Services\FileStorage\FileStorage;

final readonly class UpdateTool
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Tool $tool, NewTool $data): bool
    {
        $attributes = $data->toArray();

        if ($data->icon) {
            $attributes['icon'] = $this->fileStorage->upload(
                folder: \config('folders.tools'),
                file: $data->icon,
            );
        }

        return $tool->update($attributes);
    }
}
