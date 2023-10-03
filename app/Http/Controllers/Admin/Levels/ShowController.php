<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Resources\V1\LevelResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Level;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Level $level): Responsable
    {
        return new ModelResponse(
            data: new LevelResource(
                resource: $level,
            ),
        );
    }
}
