<?php

declare(strict_types=1);

namespace App\Actions\V1\Skills;

use App\DataObjects\V1\Skills\NewSkill;
use App\Models\Skill;
use App\Services\FileStorage\FileStorage;

final readonly class UpdateSkill
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Skill $skill, NewSkill $data): bool
    {
        $attributes = $data->toArray();

        if ($data->icon) {
            $attributes['icon'] = $this->fileStorage->upload(
                folder: \config('folders.skills'),
                file: $data->icon,
            );
        }

        return $skill->update($attributes);
    }
}
