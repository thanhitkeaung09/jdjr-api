<?php

declare(strict_types=1);

namespace App\Actions\V1\Skills;

use App\Models\Skill;
use App\Services\FileStorage\FileStorage;

final readonly class DeleteSkill
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Skill $skill): bool
    {
        $this->fileStorage->delete($skill->icon);

        return $skill->delete();
    }
}
