<?php

declare(strict_types=1);

namespace App\Actions\V1\Levels;

use App\DataObjects\V1\Levels\NewLevel;
use App\Models\Level;

final readonly class CreateLevel
{
    public function handle(NewLevel $data): Level
    {
        return Level::query()->create($data->toArray());
    }
}
