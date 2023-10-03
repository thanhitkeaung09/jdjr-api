<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Notifications;

use App\Actions\V1\Notifications\FetchNotifications;
use App\Http\Resources\V1\Notifications\NotificationResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class IndexController
{
    public function __construct(
        private FetchNotifications $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: NotificationResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
