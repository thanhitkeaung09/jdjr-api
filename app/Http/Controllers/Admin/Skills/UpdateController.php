<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Skills;

use App\Actions\V1\Skills\UpdateSkill;
use App\Http\Requests\V1\Skills\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Skill;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateSkill $updateSkill,
    ) {
    }

    public function __invoke(Skill $skill, UpsertRequest $request): Responsable
    {
        $status = $this->updateSkill->handle(
            skill: $skill,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
