<?php

declare(strict_types=1);

namespace App\Actions\V1\Levels;

use App\Models\Level;

final readonly class DeleteLevel
{
    public function handle(Level $level): bool
    {
        return $level->delete();
    }
}
