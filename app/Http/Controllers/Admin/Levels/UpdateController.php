<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Levels;

use App\Actions\V1\Levels\UpdateLevel;
use App\Http\Requests\V1\Levels\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Level;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateLevel $updateLevel,
    ) {
    }

    public function __invoke(Level $level, UpsertRequest $request): Responsable
    {
        $status = $this->updateLevel->handle(
            level: $level,
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
