<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Experiences;

use App\Http\Resources\V1\ExperienceResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Experience;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Experience $experience): Responsable
    {
        return new ModelResponse(
            data: new ExperienceResource(
                resource: $experience->load('level'),
            ),
        );
    }
}
