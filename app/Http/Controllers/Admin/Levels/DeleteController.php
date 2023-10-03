<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Levels;

use App\Actions\V1\Levels\DeleteLevel;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Level;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteLevel $deleteLevel,
    ) {
    }

    public function __invoke(Level $level): Responsable
    {
        $status = $this->deleteLevel->handle($level);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
