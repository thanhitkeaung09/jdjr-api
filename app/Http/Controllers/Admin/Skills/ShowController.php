<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Skills;

use App\Http\Resources\V1\SkillResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Skill;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Skill $skill): Responsable
    {
        return new ModelResponse(
            data: new SkillResource(
                resource: $skill,
            ),
        );
    }
}
