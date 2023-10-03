<?php

declare(strict_types=1);

namespace App\Actions\V1\Levels;

use App\DataObjects\V1\Levels\NewLevel;
use App\Models\Level;

final readonly class UpdateLevel
{
    public function handle(Level $level, NewLevel $data): bool
    {
        return $level->update($data->toArray());
    }
}
