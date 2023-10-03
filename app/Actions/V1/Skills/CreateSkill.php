<?php

declare(strict_types=1);

namespace App\Actions\V1\Skills;

use App\DataObjects\V1\Skills\NewSkill;
use App\Models\Skill;
use App\Services\FileStorage\FileStorage;

final readonly class CreateSkill
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(NewSkill $data): Skill
    {
        $path = $this->fileStorage->upload(
            folder: \config('folders.skills'),
            file: $data->icon,
        );

        return Skill::query()->create(
            attributes: [
                ...$data->toArray(),
                'icon' => $path,
            ],
        );
    }
}
