<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Notifications;

use App\Actions\V1\Auth\CheckOwner;
use App\Actions\V1\Notifications\ReadNotification;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Notification;
use Illuminate\Contracts\Support\Responsable;

final readonly class ReadController
{
    public function __construct(
        private CheckOwner $checkOwner,
        private ReadNotification $readNotification,
    ) {
    }

    public function __invoke(Notification $notification): Responsable
    {
        $this->checkOwner->handle($notification->user);

        $status = $this->readNotification->handle($notification);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.read.success') :
                    \trans('message.read.fail'),
            ],
        );
    }
}
