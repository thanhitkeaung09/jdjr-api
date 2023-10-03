<?php

declare(strict_types=1);

namespace App\Actions\V1\Experiences;

use App\Models\Level;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchLevels
{
    public function handle(): Builder
    {
        return Level::query()->orderBy('name');
    }
}
