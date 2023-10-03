<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Ping;

use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;

final class ShowController
{
    public function __invoke(): Responsable
    {
        return new MessageResponse(
            data: [
                'message' => \trans('message.service.online'),
            ]
        );
    }
}
