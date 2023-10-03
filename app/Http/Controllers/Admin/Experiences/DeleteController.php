<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Experiences;

use App\Http\Responses\V1\MessageResponse;
use App\Models\Experience;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __invoke(Experience $experience): Responsable
    {
        return new MessageResponse(
            data: [
                'message' => $experience->delete() ?
                    trans('message.delete.success') :
                    trans('message.delete.fail'),
            ],
        );
    }
}
