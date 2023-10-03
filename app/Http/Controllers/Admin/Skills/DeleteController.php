<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Skills;

use App\Actions\V1\Skills\DeleteSkill;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Skill;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteSkill $deleteSkill,
    ) {
    }

    public function __invoke(Skill $skill): Responsable
    {
        $status = $this->deleteSkill->handle($skill);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
